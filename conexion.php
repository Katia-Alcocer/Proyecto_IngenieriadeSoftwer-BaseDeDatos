<?php
$serverName = "sqlserver_container"; // Nombre del contenedor en Docker
$connectionOptions = array(
    "Database" => "ProyectoHerreriaUG",
    "Uid" => "sa",
    "PWD" => "TuPassword123!",
    "CharacterSet" => "UTF-8"
);

$conn = sqlsrv_connect($serverName, $connectionOptions);

if ($conn === false) {
    die(print_r(sqlsrv_errors(), true));
}
?>
