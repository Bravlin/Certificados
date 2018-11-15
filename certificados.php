<?php
    $requiere_sesion = true;
    require('lib/sesion-redireccion.php');
    require('lib/funcdb.php');
    $db = conectadb();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Certificados - Eventu</title>
    <?php require('comun/head-navegacion.php'); ?>
</head>
<body>
    <?php require('comun/navbar.php'); ?>
    <div class="container-fluid">
        <div class="row">
            <?php require('comun/barra-vertical.php'); ?>
            <div class="col-12 col-md-9 col-lg-10 py-5">
                <h1 class="mb-5 text-center">Certificados</h1>
                <div class="table-responsive">
                    <table class="table table-striped table-hover table-sm">
                        <thead class="thead-dark">
                            <tr>
                                <th scope="col">Evento</th>
                                <th scope="col">Nombre</th>
                                <th scope="col">Apellido</th>
                                <th scope="col">E-mail</th>
                                <th scope="col">Fecha de emisión</th>
                                <th scope="col">Tipo</th>
                                <th scope="col">Archivo</th>
                                <th scope="col"></th>
                            </tr>
                        </thead>
                        <tbody id="body-certificados">
                            <?php
                                if (isset($_REQUEST['idEvento'])){
                                    $idEvento = $_REQUEST['idEvento'];
                                    $certif_query = mysqli_query($db,
                                        "SELECT e.nombre AS evento,
                                        p.nombre, p.apellido,
                                        c.fecha_emision, c.nombre_certificado AS archivo, c.email_enviado AS email,
                                        i.tipo
                                        FROM certificado c
                                        INNER JOIN inscripcion i ON c.fk_inscripcion = i.id_inscripcion
                                        INNER JOIN evento e ON i.fk_evento = e.id_evento
                                        INNER JOIN perfil p ON i.fk_perfil = p.id
                                        WHERE id_evento = $idEvento
                                        ORDER BY p.nombre, p.apellido;");
                                    while ($certif = mysqli_fetch_array($certif_query)){
                                        $ruta_archivo =  "certificados/".$certif['archivo']; 
                                        echo '<tr>
                                            <td>'.$certif['evento'].'</td>
                                            <td>'.$certif['nombre'].'</td>
                                            <td>'.$certif['apellido'].'</td>
                                            <td>'.$certif['email'].'</td>
                                            <td>'.date('Y-m-d', strtotime($certif['fecha_emision'])).'</td>
                                            <td>'.$certif['tipo'].'</td>
                                            <td><a href="'.$ruta_archivo.'">Ver</a></td>
                                            <td>
                                                <button class="eliminar-certificado btn btn-danger">
                                                    Borrar
                                                </button>
                                            </td>
                                        </tr>';
                                    }
                                } 
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <?php require('comun/barra-fondo.php'); ?>
</body>
</html>