<?php
require_once('init.class.php');
try {
    $db_host     = '192.168.12.16';
    $db_user     = 'arzhanov';
    $db_password = 'UksZcnt772a';
    $db_name     = 'arzhanov_phoenix';

    $DBH = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_password);
}
catch (PDOException $e) {
    echo $e->getMessage();
    file_put_contents('../PDOErrors.txt', $e->getMessage(), FILE_APPEND);
}
$DBH->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);
$DBH->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$initClass = new Init($DBH);
$result = $initClass->get();
var_dump($result);
