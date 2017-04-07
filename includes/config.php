<?php

$host = '';
$user = '';
$password = '';
$dbname = '';


$conn = mysqli_connect($host, $user, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

date_default_timezone_set('UTC');
if(isset($_COOKIE['moto_user_info']))
{
  session_id($_COOKIE['moto_user_info']);
}
session_start();

define("API_BASE_URL", "REQUEST URL");


?>
