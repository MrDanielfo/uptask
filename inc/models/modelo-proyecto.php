<?php

$tipo = $_POST['tipo']; 


if($tipo === 'crear') {

    $proyecto = $_POST['proyecto']; 

    //echo json_encode($_POST); 

    include '../functions/conexion.php'; 

   try {

        $stmt = $conexion->prepare("INSERT INTO proyectos (nombre) VALUES (?)");
        $stmt->bind_param("s", $proyecto);
        $stmt->execute();
        // Loguear al usuario 
        if ($stmt->affected_rows > 0) {
            
            $respuesta = array(
                "mensaje" => "correcto",
                "proyecto" => $proyecto,
                "tipo" => $tipo,
                "id"   => $stmt->insert_id
            );


        } else {
            $respuesta = array(
                "mensaje" => "El password es incorrecto"
            );

        }
            
        $stmt->close();
        $conexion->close(); 
    

    } catch(Exception $e) {

        $respuesta = array(
            "error"  => $e->getMessage()
        ); 

    } 


    echo json_encode($respuesta); 




}