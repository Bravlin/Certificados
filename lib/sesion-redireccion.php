<?php
    session_start();
    if ($requiere_sesion){
        if (!isset($_SESSION['id_administrador'])){
            header("location: /login.php");
            die();
        }
    }
    elseif (isset($_SESSION['id_administrador'])){
        header("location: /index.php");
        die();
    }
?>