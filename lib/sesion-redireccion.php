<?php
    session_start();
    include_once(__DIR__."/../config.php");

    if ($requiere_sesion){
        if (!isset($_SESSION['id_administrador'])){
            header("location: ".URL."/login.php");
            die();
        }
    }
    elseif (isset($_SESSION['id_administrador'])){
        header("location: ".URL."/index.php");
        die();
    }
?>
