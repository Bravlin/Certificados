<?php
    $requiere_sesion = true;
    require('lib/sesion-redireccion.php');
    require('lib/funcdb.php');
    $db = conectadb(); 
?>

<!DOCTYPE html>
<html>
<head>
    <title>Perfiles - FICertif</title>
    <?php require('comun/head-navegacion.php'); ?>
    <link rel="stylesheet" type="text/css" href="css/item-perfil.css">
    <style>
        .agregar-perfil{
            color: var(--ing-verde);
            text-decoration: none;
            font-size: 1.75em;
        }
        
        .agregar-perfil:hover{
            color: var(--ing-azul);
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
                <h1 class="mb-5 text-center">Administrador de perfiles</h1>
                <div class="mb-3">
                    <a href="agregar-perfil.php" class="agregar-perfil">
                        <i class="fa fa-plus-circle mr-1"></i>Agregar perfil
                    </a>
                </div>
                <div id="perfiles" class="row px-2">
                    <?php
                        $perfiles_query = mysqli_query($db,
                            "SELECT p.id, p.nombre, p.apellido, p.telefono, p.email,
                            p.organismo, p.cargo
                            FROM perfil p
                            ORDER BY p.nombre ASC;"
                            );
                        while ($perfil = mysqli_fetch_array($perfiles_query))
                            require('item-perfil.php');
                    ?>
                </div>
            </div>
        </div>
    </div>
    <?php require('comun/barra-fondo.php'); ?>

    <script type="text/javascript" src="/js/manejador-perfil.js"></script>
</body>
</html>