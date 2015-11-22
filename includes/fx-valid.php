<?php
function validUsername($string='') {
    $return=true;
    $alphanum=true; $consecutive=false;

    for ($i=0; $i<strlen($string); $i++) {
        $letter=$string[$i];
        if (!$alphanum && !ctype_alnum($letter)) { $alphanum=false; $consecutive=true; }
        elseif ($alphanum && !ctype_alnum($letter)) { $alphanum=false; }
    }

    if (strlen(trim($string))<USERNAME_MIN) $return=false;
    elseif (strlen(trim($string))>USERNAME_MAX) $return=false;
    elseif (USERNAME_ALPHA==0 && preg_match('/[A-Za-z]/', $string)) $return=false;
    elseif (USERNAME_NUM==0 && preg_match('/\d/', $string)) $return=false;
    elseif (USERNAME_UNDERSCORE==0 && (substr_count($string, '_')>0 || substr_count($string, '-')>0)) $return=false;
    elseif (USERNAME_SPACES==0 && preg_match('/\s/', $string)) $return=false;
    elseif (USERNAME_SPECCHARS==0 && !preg_match('/^[A-Za-z0-9_\-\s]+$/', $string)) $return=false;
    elseif (!ctype_alnum($string[0]) || !ctype_alnum($string[strlen($string)-1])) $return=false;
    elseif ($consecutive) $return=false;

    return $return;
}

function validPassword($string='') {
    $return=true;
    $alphanum=true; $consecutive=false;

    for ($i=0; $i<strlen($string); $i++) {
        $letter=$string[$i];
        if (!$alphanum && !ctype_alnum($letter)) { $alphanum=false; $consecutive=true; }
        elseif ($alphanum && !ctype_alnum($letter)) { $alphanum=false; }
    }

    if (strlen(trim($string))<PASSWORD_MIN) $return=false;
    elseif (strlen(trim($string))>PASSWORD_MAX) $return=false;
    elseif (PASSWORD_ALPHA==0 && preg_match('/[A-Za-z]/', $string)) $return=false;
    elseif (PASSWORD_NUM==0 && preg_match('/\d/', $string)) $return=false;
    elseif (PASSWORD_UNDERSCORE==0 && (substr_count($string, '_')>0 || substr_count($string, '-')>0)) $return=false;
    elseif (PASSWORD_SPACES==0 && preg_match('/\s/', $string)) $return=false;
    elseif (PASSWORD_SPECCHARS==0 && !preg_match('/^[A-Za-z0-9_\-\s]+$/', $string)) $return=false;
    elseif (!ctype_alnum($string[0]) || !ctype_alnum($string[strlen($string)-1])) $return=false;
    elseif ($consecutive) $return=false;

    return $return;
}

function validEmail($string='') {
    $return=true;
    if (!(filter_var($string, FILTER_VALIDATE_EMAIL) && preg_match('/@.+\./', $string))) $return=false;

    return $return;
}
?>