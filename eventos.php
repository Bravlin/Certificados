<?php
    $requiere_sesion = true;
    require('lib/sesion-redireccion.php');
    require('lib/funcdb.php');
    $db = conectadb();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Eventos - Eventu</title>
    <?php require('comun/head-navegacion.php'); ?>
    <link rel="stylesheet" type="text/css" href="/css/item-consulta.css">
    <style>
        .agregar-evento{
            color: var(--eventu-red);
            text-decoration: none;
            font-size: 1.75em;
        }
        
        .agregar-evento:hover{
            color: var(--eventu-pink);
            text-decoration: none;
        }
    </style>
</head>
<body>
    <?php require('comun/navbar.php'); ?>
    <div class="container-fluid">
        <div class="row">
            <?php require('comun/barra-vertical.php'); ?>
            <div class="col-12 col-md-9 col-lg-10 py-5">
                <h1 class="mb-5 text-center">Administrador de eventos</h1>
                <div class="mb-3">
                    <a href="agregar-evento.php" class="agregar-evento"><i class="fa fa-plus-circle mr-1"></i>Agregar evento</a>
                </div>
                <div class="row">
                    <?php
                        $eventos_query = mysqli_query($db,
                            "SELECT e.id_evento, e.nombre AS nombre_evento, e.fecha_comienzo, e.fecha_creacion,
                            e.direccion_calle, e.direccion_altura,
                            ciudad.nombre AS nombre_ciudad,
                            provincia.nombre AS nombre_provincia
                            FROM evento e
                            INNER JOIN ciudad ON ciudad.id_ciudad = e.fk_ciudad
                            INNER JOIN provincia ON provincia.id_provincia = ciudad.fk_provincia
                            ORDER BY e.nombre ASC;");
                        while ($evento = mysqli_fetch_array($eventos_query))
                            require('item-consulta.php');
                    ?>
                </div>
            </div>
        </div>
    </div>
    <?php require('comun/barra-fondo.php'); ?>
</body>
</html>