<?php

include 'class.mysqli.php';

define('DB_USERNAME', 'test_db');
define('DB_PASSWORD', 'test1234');
define('DB_DATABASE', 'test_db');

$stmt = dbi::conn()->prepare("select * from tab");
$stmt->execute();

while (($row = $stmt->fetch())) {
  print_r($row);
}
