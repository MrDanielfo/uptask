<?php

$tipo = $_POST ['tipo'];

if ($tipo === 'crear') {

    $tarea = $_POST['nombre']; 
    $idProyecto = (int) $_POST['id_proyecto']; 

    //echo json_encode($_POST); 

    include '../functions/conexion.php';

    try {

        $stmt = $conexion->prepare("INSERT INTO tareas (nombre, id_proyecto) VALUES (?, ?)");
        $stmt->bind_param("si", $tarea, $idProyecto);
        $stmt->execute();
        // Loguear al usuario 
        if ($stmt->affected_rows > 0) {

            $respuesta = array(
                "mensaje" => "correcto",
                "tarea" => $tarea,
                "tipo" => $tipo,
                "id" => $stmt->insert_id,
                "id_proyecto" => $idProyecto
            );


        } else {
            $respuesta = array(
                "mensaje" => "El password es incorrecto"
            );

        }

        $stmt->close();
        $conexion->close();


    } catch (Exception $e) {

        $respuesta = array(
            "error" => $e->getMessage()
        );

    }


    echo json_encode($respuesta);

}

if($tipo === 'cambiar') {

    $idTarea = $_POST['idTarea'];
    $estado = $_POST['estado'];

    include '../functions/conexion.php';

    try {

        $stmt = $conexion->prepare("UPDATE tareas SET estado = ? WHERE id = ?");
        $stmt->bind_param("ii", $estado, $idTarea);
        $stmt->execute();
        // Loguear al usuario 
        if ($stmt->affected_rows > 0) {

            $respuesta = array(
                "mensaje" => "correcto"
            );

        } else {
            $respuesta = array(
                "mensaje" => "Hubo un error con la base de datos"
            );

        }

        $stmt->close();
        $conexion->close();

    } catch (Exception $e) {

        $respuesta = array(
            "error" => $e->getMessage()
        );

    }

    echo json_encode($respuesta);
}

if ($tipo === 'eliminar') {

    $idEliminar = $_POST['idEliminar'];

    include '../functions/conexion.php';

    try {

        $stmt = $conexion->prepare("DELETE FROM tareas WHERE id = ?");
        $stmt->bind_param("i", $idEliminar);
        $stmt->execute();
        // Loguear al usuario 
        if ($stmt->affected_rows > 0) {
            $respuesta = array(
                "mensaje" => "correcto"
            );
        } else {
            $respuesta = array(
                "mensaje" => "Hubo un error con la base de datos"
            );
        }

        $stmt->close();
        $conexion->close();

    } catch (Exception $e) {

        $respuesta = array(
            "error" => $e->getMessage()
        );

    }


    echo json_encode($respuesta);



}