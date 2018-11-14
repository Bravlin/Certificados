<?php
    require('funcdb.php');
    $db = conectadb();
    $tipos = array("Asistente", "Evaluador", "Organizador", "Disertante");
    $idEvento = (isset($_REQUEST['idEvento'])) ? $_REQUEST['idEvento'] : "";
    $idPerfil = (isset($_REQUEST['idPerfil'])) ? $_REQUEST['idPerfil'] : "";
    $tipo = (isset($_REQUEST['tipo'])) ? $_REQUEST['tipo'] : "";
    if ($idEvento != "" && $idPerfil != "" && in_array($tipo, $tipos)){
        mysqli_query($db, 
            "INSERT INTO inscripcion (tipo, fk_perfil, fk_evento)
            VALUES ('$tipo', $idPerfil, $idEvento)");
    }
?>