<?php
$servername = "localhost";
$database = "thoth_bd";
$username = "root";
$password = "";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $database);
$cmd = new PDO("mysql:host=$servername;dbname=$database;utf8", $username, $password);


//////////////////////
// Check connection
if (!$conn) {
      die("Connection failed: " . mysqli_connect_error());
}

date_default_timezone_set("America/Bogota");
mysqli_set_charset($conn, 'utf8');

?>