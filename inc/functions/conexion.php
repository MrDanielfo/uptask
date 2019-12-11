<?php

    $conexion = new mysqli("localhost", "root", "root", "uptask_torre");
    //var_dump($conexion->ping());

    if($conexion->connect_error) {
        echo $conexion->connect_error; 
    }

    $conexion->set_charset('utf8'); 

?>
