<?php

include_once('functions.php');

$regex_pattern_for_latin = '/\p{Latin}+/';
$regex_pattern_for_number = '/\d+/';

/**
 * getting files in folder with particular ext
 */
$files = get_files_in_folder("./datafiles/", ["ixt"], false);

echo "List of Files with ixt extension in Folder";

dump($files);

$file_names = [];

foreach($files as $file)
{
    $file_name = pathinfo($file, PATHINFO_FILENAME);
    $file_ext = pathinfo($file, PATHINFO_EXTENSION);

    if (preg_match($regex_pattern_for_latin, $file_name) > 0 && preg_match($regex_pattern_for_number, $file_name) > 0)
    {
        $file_names[] = $file_name . "." . $file_ext;
    }
}

sort($file_names);

echo "Files which have Latin charcter with number sort by asc";

dump($file_names);