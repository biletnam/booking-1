<?php

// This file exists for the purpose of setting up the database.

require_once '../config.php';

$sql_db = "CREATE DATABASE IF NOT EXISTS `".MYSQL_DB."`;";

$sql_tables = "CREATE TABLE IF NOT EXISTS `".MYSQL_DB."`.
              `reservation` (
              `id` INT(4) NOT NULL AUTO_INCREMENT ,
              `destination` TEXT NOT NULL ,
              `insurance` BOOLEAN NOT NULL ,
              `nbr_persons` INT(2) NOT NULL ,
              `persons` BLOB NOT NULL ,
              PRIMARY KEY (`id`)) ENGINE = InnoDB;";

try
{
    $db = new PDO('mysql:host='.MYSQL_HOST.';', MYSQL_USER, MYSQL_PASS);
    $db->exec($sql_db);
    $db->exec($sql_tables);
}
catch (Exception $e)
{
    die($e->getMessage());
}

print("DB Installed");

?>
