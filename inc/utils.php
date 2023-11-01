<?php

if (!function_exists('array_find')) {
    function array_find($xs, $f) {
      foreach ($xs as $x) {
        if (call_user_func($f, $x) === true)
        return $x;
    }
    return null;
  }
}

if (!function_exists('array_find_index')) {
  function array_find_index($xs, $f) {
    $i = 0;
    foreach ($xs as $x) {
      if (call_user_func($f, $x) === true) {
        return $i;
      } else {
        $i++;
      }
    }
    return null;
  }
}  

if (!function_exists('hexToRgb')) {
  function hexToRgb($hex, $alpha = false) {
    $hex      = str_replace('#', '', $hex);
    $length   = strlen($hex);
    $rgb['r'] = hexdec($length == 6 ? substr($hex, 0, 2) : ($length == 3 ? str_repeat(substr($hex, 0, 1), 2) : 0));
    $rgb['g'] = hexdec($length == 6 ? substr($hex, 2, 2) : ($length == 3 ? str_repeat(substr($hex, 1, 1), 2) : 0));
    $rgb['b'] = hexdec($length == 6 ? substr($hex, 4, 2) : ($length == 3 ? str_repeat(substr($hex, 2, 1), 2) : 0));
    if ( $alpha ) {
      $rgb['a'] = $alpha;
    }
    return $rgb;
  }
}