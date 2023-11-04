<?php
require_once('common.php');

$json = file_get_contents("php://input");

if (!$json)
{
    die("request json not found");
}

$json = json_decode($json);

if (!$json)
{
    die("fail to decode json");    
}

$data = objToArray($json);

if (!isset($data['no_of_records_to_generate']))
{
    die("no_of_records_to_generate is not found in json");
}

for($i = 1; $i <= $data['no_of_records_to_generate']; $i++)
{
    $record = [
        "name" => get_random_name(),
        "created" => get_random_date_time(),
    ];

    if (!json_insert($record))
    {
        die("Fail to Save Record");
    }
}


echo 1; exit;