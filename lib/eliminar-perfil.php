<?php
    require('funcdb.php');
    $db = conectadb();
    $idPerfil = $_REQUEST['idPerfil'];
    mysqli_query($db, "DELETE FROM perfil WHERE id = $idPerfil;");
?>