<?php

$servername = "localhost";
$username = "root";
$password = "";

try {
    $connectionDB = new PDO("mysql:host=$servername;dbname=cms_db", $username, $password);
  // set the PDO error mode to exception
  $connectionDB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e){
    echo "Connection failed: " . $e->getMessage();
}

?>