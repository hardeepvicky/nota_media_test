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

$delete_record = objToArray($json);

if (!isset($delete_record['id']) && empty($delete_record['id']))
{
    die("id not found in json");
}

if (!json_delete($delete_record['id']))
{
    die("Record not found to delete");
}

echo 1; exit;