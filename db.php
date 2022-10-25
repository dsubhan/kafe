<?php
$server = '<db server>';
$user = '<user>';
$pass = '****';
$dbname = '<db name>';
$con = mysqli_connect($server, $user, $pass) or die("Can't connect");
mysqli_select_db($con, $dbname);
?>
