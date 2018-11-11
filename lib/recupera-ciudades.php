<?php
    require('funcdb.php');
    $db = conectadb();
    if (!empty($_REQUEST["id_provincia"])){
        //Recupera las ciudades según la provincia
        $ciudad_query = mysqli_query($db, "SELECT * FROM ciudad WHERE fk_provincia = ".$_REQUEST['id_provincia']." ORDER BY nombre ASC");
        while ($ciudad = mysqli_fetch_array($ciudad_query))
            echo '<option value="'.$ciudad['id_ciudad'].'">'.$ciudad['nombre'].'</option>';
    }
    else {
        echo '<option value="">Primero elija una provincia</option>';
    }
?>