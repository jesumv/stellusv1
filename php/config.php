<?php

/*conexion a la base de datos */

$mysql_hostname = "localhost";
$mysql_user = "test";
$mysql_password = "test";
$mysql_database = "stellus1";


$mysqli = new mysqli($mysql_hostname, $mysql_user, $mysql_password, $mysql_database);

if($mysqli->connect_errno > 0){
    die('No se estableció conexión a la base de datos [' . $mysqli->connect_error . ']');
}


?>