<?php
//$con = mysqli_connect("localhost","root","","handyman");
$con = new PDO("mysql:host=localhost; dbname=handyman", "root", "");

$con->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
/*if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
} 
echo "Connected successfully";
*/
?>