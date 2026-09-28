<?php
header('Content-Type:application/json');

$server="localhost";
$username="root";
$password="";
$database="api_learning";

$conn=mysqli_connect($server,$username,$password,$database);

if(!$conn){
    echo "Connection Failed";
}
// else{
//     echo "Connected Successfully";
// }
// mysqli_set_charset($conn, "utf8mb4");

