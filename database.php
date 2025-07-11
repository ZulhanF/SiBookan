<?php
$hostname = "localhost";
$username = "root";
$password = "";
$database = "SiBookan";

$db = mysqli_connect($hostname, $username, $password, $database);

if (!$db) {
    die("Connection failed: " . mysqli_connect_error());
}
?>