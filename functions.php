<?php

function dump($array)
{
    $callBy = debug_backtrace()[0];
    echo "<pre>";
    echo "<b>" . $callBy['file'] . "</b> At Line : " . $callBy['line'];
    echo "<br/>";
    print_r($array);
    echo "</pre>";
}

function get_files_in_folder($path, $exts = array(), $recursive = false)
{
    $files = scandir($path);
    $ret_files = array();

    foreach ($files as $k => $file) {
        $f = $path . $file . "/";

        if ($file == '.' || $file == '..') {
        } else if ($recursive && is_dir($f)) {
            $ret_files = array_merge($ret_files, get_files_in_folder($f, $exts, $recursive));
        } else if (!empty($exts) && !in_array(pathinfo($file, PATHINFO_EXTENSION), $exts)) {
        } else {
            $ret_files[] = $path . $file;
        }
    }

    return $ret_files;
}