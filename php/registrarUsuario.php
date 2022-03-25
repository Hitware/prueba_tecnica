<?php
require_once 'conexion.php';
$con = con();

$nombres = $_POST["nombres"]; 
$apellidos = $_POST["apellidos"]; 

$tabla= "CREATE TABLE IF NOT EXISTS `usuarios`(
    id_usuario INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nombres VARCHAR(50) NOT NULL,
    apellidos VARCHAR(60) NOT NULL
    )";
   mysqli_query($con,$tabla);

   $datos="INSERT INTO usuarios (nombres,apellidos) VALUES ('$nombres','$apellidos')";
    $insertar = mysqli_query($con, $datos);





?>