<?php

//echo json_encode($_POST); 

$tipo = $_POST['tipo'];
$usuario = $_POST['usuario'];
$password = $_POST['password'];

if($tipo === 'crear') {
    // Código para crear los administradores
    $opciones = array(
        "cost" => 12
    );
    $hash_password = password_hash($password, PASSWORD_BCRYPT, $opciones);

    // importar conexion 
    include '../functions/conexion.php';

    try {

        $stmt = $conexion->prepare("INSERT INTO usuarios (usuario, pass) VALUES (?, ?)"); 
        $stmt->bind_param("ss", $usuario, $hash_password); 
        $stmt->execute();

        if($stmt->affected_rows > 0) {
            $respuesta = array(
                "usuario" => $usuario,
                "password" => $password,
                "mensaje"  => "correcto",
                "tipo"     => $tipo
            );
        } else {
            $respuesta = array(
                "mensaje" => "error"
            );

        }
            
        $stmt->close();
        $conexion->close(); 

    } catch(Exception $e) {
        $respuesta = array(
            "error" => $e->getMessage()
        );

    }

    echo json_encode($respuesta); 

}

if($tipo === 'login') {

    

    include '../functions/conexion.php';

    try {

        $stmt = $conexion->prepare("SELECT id, usuario, pass FROM usuarios WHERE usuario = ?");
        $stmt->bind_param("s", $usuario);
        $stmt->execute();
        // Loguear al usuario 
        $stmt->bind_result($idUsuario, $nombreUsuario, $passUsuario);
        $stmt->fetch();

        if($nombreUsuario) {
            // verificar password
            if(password_verify($password, $passUsuario)) {

                // Iniciar Sesión
                session_start(); 
                $_SESSION['nombre'] = $usuario; 
                $_SESSION['id']     = $idUsuario;
                $_SESSION['login']  = true; 

                $respuesta = array(
                    "mensaje" => "correcto",
                    "nombre" => $nombreUsuario,
                    "tipo"   => $tipo
                );

            
            } else {
                $respuesta = array(
                    "mensaje" => "El password es incorrecto"
                );

            }

        } else {
            $respuesta = array(
                "mensaje" => "Usuario no existe"
            );

        }
            /*echo '<script>
                  window.location.href = "index.php"
                  </script>';*/
        
        $stmt->close();
        $conexion->close(); 

    } catch(Exception $e) {
        $respuesta = array(
            "mensaje" => $e->getMessage()
        ); 
    }

    echo json_encode($respuesta);

}