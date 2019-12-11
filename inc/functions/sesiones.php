<?php


function usuarioAutenticado() {

    if(!revisarUsuario()) {

        header('Location:login.php');
        exit(); 
    }

}



function revisarUsuario() {

    return isset($_SESSION['nombre']); 

}
// es necesario abrir siempre la sesión 
session_start(); 
usuarioAutenticado(); 

