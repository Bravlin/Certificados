<?php
    session_start();
    if ($_SESSION['id_administrador'] != ""){
        session_destroy();
        session_write_close();
    }
    header("location: ../login.php");
?>