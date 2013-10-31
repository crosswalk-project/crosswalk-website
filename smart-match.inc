<?php
function file_smart_match ($filepath) {
    if (file_exists ($filepath) && !is_dir ($filepath))
        return $filepath;
    $dir = dir_smart_match (dirname ($filepath));
    if (!$dir)
        return false;
    $lower = strtolower (basename ($filepath));
    $files = glob ($dir.'/*');
    foreach ($files as $f) {
        if (is_dir ($f))
            continue;
        /* Check if this globbed filename matches a markdown file, return
         * the base name */
        if (preg_match (
                '/(([0-9]*-)?(.*))((\.md)|(\.mediawiki)|(\.org)|(\.php))$/i', 
                basename ($f), $matches)) {
            if (strtolower ($matches[3]) == $lower)
                return $f;
        }
        
        /* Check if this globbed filename matches a markdown file that has been
         * converted to .html already. Return it IFF the original markdown
         * does not exist */
        if (preg_match (
                '/(([0-9]*-)?(.*))((\.md)|(\.mediawiki)|(\.org)|(\.php))(.html)$/i', 
                basename ($f), $matches)) {
            if (strtolower ($matches[3]) == $lower &&
                    !file_exists (dirname ($filepath).'/'.$matches[1].$matches[3]))
                return $f;
        }
    }
    return false;
}

function dir_smart_match ($filepath) {
    if (file_exists ($filepath) && is_dir (dirname ($filepath)))
        return basename ($filepath);
    $lower = strtolower (basename ($filepath));
    $files = glob (dirname ($filepath).'/*');
    foreach ($files as $f) {
        if (!is_dir ($f))
            continue;
        /* Check if this globbed filename matches a directory */
        if (preg_match ('/(([0-9]*-)?(.*))$/i', basename ($f), $matches)) {
            if (strtolower ($matches[3]) == $lower)
                return $f;
        }
    }
    return false;
}