<?php
$host="localhost";
$username="root";
$password="Singh@9411";
$dbname="phpproject";

$conn =mysqli_connect($host,$username,$password,$dbname);
if(! $conn ) [
    die('Connection Failed'.mysqli_connect_error())
]
?>
