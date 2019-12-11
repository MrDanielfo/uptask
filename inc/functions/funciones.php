<?php

function obtenerPaginaActual() {

    $archivo = basename($_SERVER['PHP_SELF']); 
    $pagina = str_replace(".php", "", $archivo); 
    return $pagina; 

}

/* consultas */ 

function obtenerProyectos() {

    include 'conexion.php'; 

    try{

        return $conexion->query("SELECT * FROM proyectos");

    } catch(Exception $e) {

        echo "Error" . $e->getMessage();

        return false;

    }

}

// obtener nombre del Proyecto 

function obtenerNombreProyecto($id = null) {

    include 'conexion.php';

    try {

        return $conexion->query("SELECT nombre FROM proyectos WHERE id = {$id}");

    } catch (Exception $e) {

        echo "Error" . $e->getMessage();

        return false;

    }
}

function obtenerTareasId($idProyecto = null) {

    include 'conexion.php';

    try {

        return $conexion->query("SELECT * FROM tareas WHERE id_proyecto = {$idProyecto}");

    } catch (Exception $e) {

        echo "Error" . $e->getMessage();

        return false;

    }
}


