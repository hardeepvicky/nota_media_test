<?php

function dump($arg)
{
    $callBy = debug_backtrace()[0];
    echo "<pre>";
    echo "<b>" . $callBy['file'] . "</b> At Line : " . $callBy['line'];
    echo "<br/>";
    
    if (is_string($arg))
    {
        echo htmlspecialchars($arg);
    }
    else
    {
        print_r($arg);
    }
    
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

function get_random_string($length)
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[random_int(0, $charactersLength - 1)];
    }
    return $randomString;
}

function get_random_date_time()
{
    $int= mt_rand(1262055681,1262055681);

    $string = date("Y-m-d H:i:s",$int);

    return $string;
}

function DOMDocument_get_html_from_tag(DOMDocument $dom)
{
    
}