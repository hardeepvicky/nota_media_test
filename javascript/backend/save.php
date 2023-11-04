<?php
require_once('common.php');

$json = file_get_contents("php://input");

if (!$json)
{
    die("request json not found");
}

$save_record = json_decode($json);

if (!$save_record)
{
    die("fail to decode json");
}

$save_record = objToArray($save_record);

if (!isset($save_record['name']))
{
    die("name is not found in save_record");
}

if (isset($save_record['id']) && $save_record['id'])
{
    if (!json_update($save_record))
    {
        die("Fail To Update Record");
    }
}
else
{
    $records = json_get_records();

    foreach($records as $record)
    {
        if ($save_record['name'] == $record['name'])
        {
            die("Name Already Exist");
        }
    }

    if ( !json_insert($save_record) )
    {
        die("Fail To Insert Record");
    }
}

echo 1; exit;