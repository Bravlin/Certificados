<?php
    $requiere_sesion = true;
    require('lib/sesion-redireccion.php');
    require('lib/funcdb.php');
    $db = conectadb(); 
?>

<!DOCTYPE html>
<html>
<head>
    <title>Participantes - Eventu</title>
    <?php require('comun/head-navegacion.php'); ?>
    <link rel="stylesheet" type="text/css" href="css/item-participante.css">
    <style>
        .agregar-usuario{
            color: var(--eventu-red);
            text-decoration: none;
            font-size: 1.75em;
        }
        
        .agregar-usuario:hover{
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
                <h1 class="mb-5 text-center">Administrador de participantes</h1>
                <div class="mb-3">
                    <a href="agregar-participante.php" class="agregar-usuario">
                        <i class="fa fa-plus-circle mr-1"></i>Agregar participante
                    </a>
                </div>
                <div class="row px-2 justify-content-center">
                    <?php
                        $participantes_query = mysqli_query($db,
                            "SELECT p.id, p.nombre, p.apellido, p.telefono, p.email, p.fecha,
                            p.facultad, p.universidad
                            FROM ptds p
                            ORDER BY p.nombre ASC;"
                            );
                        while ($participante = mysqli_fetch_array($participantes_query))
                            require('item-participante.php');
                    ?>
                </div>
            </div>
        </div>
    </div>
    <?php require('comun/barra-fondo.php'); ?>
</body>
</html>