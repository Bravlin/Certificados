<?php
    require('funcdb.php');
    $db = conectadb();
    $idEvento = $_REQUEST['idEvento'];
    mysqli_query($db, "DELETE FROM evento WHERE id_evento = $idEvento;");
?>