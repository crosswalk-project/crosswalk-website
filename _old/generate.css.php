<?php
header('Content-type: text/css');
$sass = getenv('SASS');
if (!$sass) {
  $sass = 'sass';
}

// get the name of the css.php file to be processed;
// on the server, it's the SCRIPT_FILENAME (i.e. the php script
// which require'd this script);
// on the command line, it's the name of the script passed to the php command
$f = '';
if (isset($_SERVER)) {
  $f = $_SERVER['SCRIPT_FILENAME'];
}
else if (PHP_SAPI === 'cli') {
  $f = $argv[0];
}

$basename = preg_replace ('/\.css\.php$/', '', basename($f));
$scss = @stat ($basename.'.scss');
$css = @stat ($basename.'.css');
$script = @stat (__FILE__);

$rebuild = false;
if (!$css ||
    $scss['mtime'] > $css['mtime'] ||
    $script['mtime'] > $css['mtime']) {
    $rebuild = true;
}

if (!$rebuild) {
    success ();
}

function success () {
    global $basename;
    require ($basename.'.css');
    exit;
}

function failure ($msg) {
    global $basename;
    ?>
/*
 * <?= strftime ("%F %r") ?>
 *
 * scss => css conversion failed:
 * <?= $msg ?>
 */
body {
    background: red;
    color: red;
}
<?php
    exit;
}

if (@file_exists ('.'.$basename.'.css')) {
    @unlink ('.'.$basename.'.css');
}
if (@file_exists ('.'.$basename.'.msg')) {
    @unlink ('.'.$basename.'.msg');
}


$sourcemaps = '';
$p = @popen ($sass.' -v', 'r');
if (!$p) {
    failure ('sass not found in path', $sass);
}
while (!feof ($p)) {
    $l = trim (fgets ($p));
    if (preg_match ('/^[^0-9]*([0-9]*\.[0-9]*)\..*$/', $l, $matches)) {
        if ($matches[1] >= 3.3) {
            $sourcemaps = ' --sourcemap ';
        }
        break;
    }
}
pclose ($p);

# -C turns off the cache

$cmd = $sass.' -C '.$sourcemaps.' '.$basename.'.scss:.'.$basename.'.css';
$p = @popen ($cmd.' 2>&1', 'r');
if (!$p) {
    failure ('Error executing: '.$cmd);
}

/* Write any output from the sass command into BASENAME.msg for later retrieval (if needed) */
$m = @fopen ($basename.'.msg', 'w');
if (!$m) {
    failure ('Unable to open '.$basename.'.msg for writing');
}
fwrite ($m, "/* $basename.'.css' generated ".strftime ("%F %r")." via:\n *\n * ".$cmd."\n".
            " * sass output:\n");
while (!feof ($p)) {
    fwrite ($m, fgets ($p));
}
pclose ($p);

$f = @fopen ('.'.$basename.'.css', 'r');
if (!$f) {
    fwrite ($m, " * Temporary file .'.$basename.'.css not found after conversion.\n");
    fwrite ($m, " */\n");
    fclose ($m);
    failure ('Could not open .'.$basename.'.css after conversion.');
}
fwrite ($m, " */\n");
fclose ($m);


/* Translate .BASENAME.css => BASENAME.css replacing the string
 * '.BASENAME.css' with 'BASENAME.css'
 */
$c = @fopen ($basename.'.css', 'w');
if (!$c) {
    failure ('Can not create '.$basename.'.css');
}
while (!feof ($f)) {
    fwrite ($c, preg_replace ('/\.'.$basename.'\.css/', $basename.'.css', fgets ($f)));
}
fclose ($f);
fclose ($c);
/* And then remove the old .'.$basename.'.css */

unlink ('.'.$basename.'.css');
if (file_exists ('.'.$basename.'.css.map')) {
    /* Translate .BASENAME.css.map => BASENAME.css.map, replacing the string
     * '.BASENAME.css' with 'BASENAME.css'
     */
    $f = @fopen ('.'.$basename.'.css.map', 'r');
    $c = @fopen ($basename.'.css.map', 'w');
    while (!feof ($f)) {
        fwrite ($c, preg_replace ('/\.'.$basename.'\.css/', $basename.'.css', fgets ($f)));
    }
    fclose ($f);
    fclose ($c);
    unlink ('.'.$basename.'.css.map');
}

touch ($basename.'.css', $scss['mtime']);
touch ($basename.'.css.map', $scss['mtime']);

success ();
?>
