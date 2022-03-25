<?php

require_once 'conexion.php';
$con = con();

$sql = "SELECT * FROM usuarios";
$query = mysqli_query($con, $sql);

while ($field = mysqli_fetch_array($query)) {
    $nombres = $field['nombres'];
    $apellidos = $field['apellidos'];
    $array[]=array(
        'nombres'=>$nombres,
        'apellidos'=>$apellidos,
    );

}
//valicion mysql
$json_string = json_encode($array, JSON_UNESCAPED_UNICODE);
echo $json_string;
