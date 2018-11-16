<?php
   $requiere_sesion = true;
   require('lib/sesion-redireccion.php');
   require('lib/funcdb.php');
   $db = conectadb();
    
    $consulta = $_REQUEST['consulta'];
    switch ($_REQUEST['filtro']){
        case 'nombre':
            $criterio_query = "e.nombre";
            break;
    }
?>

<!DOCTYPE html>
<html>
<head>
    <title><?php echo $consulta; ?> - FICertif</title>
    <?php require('comun/head-navegacion.php'); ?>
    <link rel="stylesheet" type="text/css" href="css/item-consulta.css">
</head>
<body>
    <?php require('comun/navbar.php'); ?>
    <div class="container-fluid">
        <div class="row">
            <?php require('comun/barra-vertical.php'); ?>
            <div class="col-12 col-md-9 col-lg-10 py-5">
                <div class="row">
                    <?php
                        $eventos_query = mysqli_query($db,
                            "SELECT e.id_evento, e.nombre AS nombre_evento, e.fecha_realizacion, e.fecha_creacion,
                            e.direccion_calle, e.direccion_altura,
                            ciudad.nombre AS nombre_ciudad,
                            provincia.nombre AS nombre_provincia
                            FROM evento e
                            INNER JOIN ciudad ON ciudad.id_ciudad = e.fk_ciudad
                            INNER JOIN provincia ON provincia.id_provincia = ciudad.fk_provincia
                            WHERE $criterio_query LIKE '%$consulta%'
                            ORDER BY e.nombre ASC;");
                        while ($evento = mysqli_fetch_array($eventos_query))
                            require('item-evento.php');
                    ?>
                </div>
            </div>
        </div>
    </div>
    <?php require('comun/barra-fondo.php'); ?>
</body>
</html>