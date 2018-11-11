<?php
    $requiere_sesion = true;
    require('lib/sesion-redireccion.php');
    require('lib/funcdb.php');
    $db = conectadb(); 
?>

<!DOCTYPE html>
<html>
<head>
    <title>Usuarios - Eventu</title>
    <?php require('comun/head-navegacion.php'); ?>
    <link rel="stylesheet" type="text/css" href="css/item-usuario.css">
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
                    <a href="agregar-participante.php" class="agregar-usuario"><i class="fa fa-plus-circle mr-1"></i>Agregar usuario</a>
                </div>
                <div class="row px-2">
                    <?php
                        $usuarios_query = mysqli_query($db,
                        "SELECT u.idUsuario, u.nombres, u.apellidos, u.fechaNac, u.email, u.tipo,
                        dir.calle, dir.altura,
                        ciu.nombre AS nombreCiudad,
                        prov.nombre AS nombreProvincia
                        FROM usuarios u
                        INNER JOIN direcciones dir ON dir.idDireccion = u.idDireccion
                        INNER JOIN ciudades ciu ON ciu.codCiudad = dir.codCiudad
                        INNER JOIN provincias prov ON prov.codProvincia = ciu.codProvincia
                        ORDER BY u.nombres ASC;
                        ");
                        while ($usuario = mysqli_fetch_array($usuarios_query))
                            require('item-usuario.php');
                    ?>
                </div>
            </div>
        </div>
    </div>
    <?php require('comun/barra-fondo.php'); ?>
</body>
</html>