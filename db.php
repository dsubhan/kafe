<?php
$server = 'db.dw029.webglobe.com';
$user = 'david_subhan_cz';
$pass = '****';
$dbname = 'david_subhan_cz';
$con = mysqli_connect($server, $user, $pass) or die("Can't connect");
mysqli_select_db($con, $dbname);
?>