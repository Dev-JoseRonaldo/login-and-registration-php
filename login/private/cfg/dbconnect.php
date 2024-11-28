<?php

$server = "localhost";
$port = "3306";
$uid = "root";
$pwd = "password";
$dbname = "login_and_registration";

$conn = new mysqli("$server:$port", $uid, $pwd, $dbname);

if($conn->connect_error) {
  die("Database connection error ".$conn->connect_error);
}
