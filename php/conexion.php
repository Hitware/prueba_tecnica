<?php
/*Funcion que nos permite crear la conexiÃ³n a la base de datos*/
header('Access-Control-Allow-Origin: *'); 
function con()
{ 
$echo = mysqli_connect("localhost","root","","prueba_tecnica");
    return $echo;
}


?>