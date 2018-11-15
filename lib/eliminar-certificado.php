<?php
    require('funcdb.php');
    $db = conectadb();
    $idCertif = $_REQUEST['idCertif'];
    $certificado_query = mysqli_query($db,
        "SELECT nombre_certificado
        FROM certificado
        WHERE id_certificado = $idCertif;");
    if ($certificado = mysqli_fetch_array($certificado_query)){
        mysqli_query($db, "DELETE FROM certificado WHERE id_certificado = $idCertif;");
        unlink("../certificados/".$certificado['nombre_certificado']);
    }
?>