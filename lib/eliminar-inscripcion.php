<?php
    require('funcdb.php');
    $db = conectadb();
    $idInscrip = $_REQUEST['idInscrip'];
    mysqli_query($db, "DELETE FROM inscripcion WHERE id_inscripcion = $idInscrip;");
?>