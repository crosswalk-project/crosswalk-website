<?php
// functions for manipulating strings
function make_name ($name) {
    return preg_replace ('/_+/', ' ',
           preg_replace ('/-+/', ' ',
           preg_replace ('/^[0-9]*[-_]/', '',
           $name)));
}
?>
