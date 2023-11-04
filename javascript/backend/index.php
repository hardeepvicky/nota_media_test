<?php 
require_once('common.php');

$records = json_get_records();

header('content-type', 'application/json');

echo json_encode($records);