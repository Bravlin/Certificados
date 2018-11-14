<?php
    $requiere_sesion = true;
    require('lib/sesion-redireccion.php');
    require('lib/funcdb.php');
    require('lib/funciones-comunes.php');
    $db = conectadb();
    
    // Variables de control
    $nombre_ok = true;
    $calle_ok = true;
    $callealt_ok = true;
    $provincia_ok = true;
    $ciudad_ok = true;
    $provincia_ok = true;
    $ciudad_ok = true;
    $fecreal_ok = true;
    
    $idEvento = $_REQUEST['id_evento'];
    if (!isset($_REQUEST['confirma']) || $_REQUEST['confirma'] != 'si'){
        $eventos_query = mysqli_query($db,
            "SELECT e.id_evento, e.nombre AS nombre_evento, e.fecha_realizacion, e.fecha_creacion,
            e.descripcion, e.direccion_calle AS calle, e.direccion_altura AS altura,
            ciudad.nombre AS nombre_ciudad, ciudad.id_ciudad,
            provincia.nombre AS nombre_provincia, provincia.id_provincia
            FROM evento e
            INNER JOIN ciudad ON ciudad.id_ciudad = e.fk_ciudad
            INNER JOIN provincia ON provincia.id_provincia = ciudad.fk_provincia
            WHERE e.id_evento = $idEvento;");
        if (mysqli_num_rows($eventos_query) == 1){
            $evento = mysqli_fetch_array($eventos_query);
            $nombreEvento = $evento['nombre_evento'];
            $descripcion = $evento['descripcion'];
            $calle = $evento['calle'];
            $altura = $evento['altura'];
            $codProvincia = $evento['id_provincia'];
            $codCiudad = $evento['id_ciudad'];
            $fechaRealiz = $evento['fecha_realizacion'];
        }
    }
    else {
        // Procesamiento del formulario
        $nombreEvento = $_REQUEST['nombre'];
        $descripcion = $_REQUEST['descripcion'];
        $calle = $_REQUEST['calle'];
        $altura = $_REQUEST['callealt'];
        $codProvincia = $_REQUEST['provincia'];
        $codCiudad = $_REQUEST['ciudad'];
        $fechaRealiz = $_REQUEST['fecreal'];
        // Validaciones
        $nombre_ok = $nombreEvento != "";
        $calle_ok = $calle != "";
        $callealt_ok = $altura > 0;
        $provincia_ok = $codProvincia != "";
        $ciudad_ok = $codCiudad != "";
        $fecreal_ok = compararFechas($fechaRealiz, date("Y-m-d h:i:sa")) > 0;
        if ($nombre_ok && $calle_ok && $callealt_ok && $provincia_ok && $ciudad_ok && $fecreal_ok){
            $nombre = mysqli_real_escape_string($db, $nombre);
            $descripcion = mysqli_real_escape_string($db, $descripcion);
            $calle = mysqli_real_escape_string($db, $calle);
            mysqli_query($db,
                "UPDATE evento
                SET nombre = '$nombreEvento', descripcion = '$descripcion', fecha_realizacion = '$fechaRealiz',
                    direccion_calle = '$calle', direccion_altura = $altura, fk_ciudad = $codCiudad
                WHERE id_evento = $idEvento;");
            header("location: evento.php?id_evento=$idEvento");
        }
    }
?>

<!DOCTYPE html>
<html>
<head>
    <title><?php echo $nombreEvento; ?> - Eventu</title>
    <?php require('comun/head-navegacion.php'); ?>
    <link rel="stylesheet" type="text/css" href="/css/evento.css">
    <link rel="stylesheet" type="text/css" href="/css/edicion-evento.css">
    <link rel="stylesheet" type="text/css" href="/css/comentario.css">
    <style>
        .alerta{
            color: red;
        }
    </style>
</head>
<body>
    <?php require('comun/navbar.php'); ?>
    <div class="container-fluid">
        <div class="row">
            <?php require('comun/barra-vertical.php'); ?>
            <div class="col-12 col-md-9 col-lg-10 py-5 px-md-5">
                <form method="POST" enctype="multipart/form-data">
                    <div class="contenedor-portada mb-5">
                        <img class="portada" alt="portada"
                            src=<?php
                                $portada = "/media/portadas-eventos/" . $evento['id_evento'] . "-p";
                                if (file_exists($portada))
                                    echo $portada;
                                else
                                    echo "/media/portadas-eventos/0-p";
                            ?>
                        >
                        <div class="contenedor-titulo px-1 px-md-3">
                            <input id="nombre" name="nombre" type="text" class="editable ed-nombre text-center" value="<?php echo $nombreEvento; ?>" required>
                        </div>
                        <?php
                            if (!$nombre_ok)
                                echo '<p class="alerta">El nombre no puede quedar vacío</p>';
                        ?>
                    </div>
                    <h5>Descripción:</h5>
                    <textarea id="descripcion" name="descripcion" class="editable ed-descripcion"><?php echo $descripcion; ?></textarea>
                    <div class="info-general row py-3 my-5">
                        <div class="col-12 col-lg-6 mb-3 mb-sm-0 row">
                            <div class="col-12 elemento-form mb-3">
                                    <label for="fechaReal"><i class="fa fa-calendar"></i> Fecha y <i class="fa fa-clock-o"></i> hora</label>
                                    <input id="fechaReal" name="fecreal" type="datetime-local" class="form-control"
                                        value="<?php echo date('Y-m-d\TH:i', strtotime($fechaRealiz)); ?>" required>
                                    <?php
                                        if (!$fecreal_ok)
                                            echo '<p class="alerta">Ingrese una fecha válida</p>';
                                    ?>
                            </div>
                            <div class="col-12 mb-2">
                                <i class="fa fa-map-marker"></i> Ubicación
                            </div>
                            <div class="col-sm-6 elemento-form mb-2">
                                <label for="calle">Calle</label>
                                <input id="calle" name="calle" type="text" class="form-control"
                                    value="<?php echo $calle; ?>" required>
                                <?php
                                    if (!$calle_ok)
                                        echo '<p class="alerta">La calle no puede quedar vacía</p>';
                                ?>
                            </div>
                            <div class="col-sm-6 elemento-form mb-2">
                                <label for="altura">Altura</label>
                                <input id="altura" name="callealt" type="number" class="form-control"
                                    value="<?php echo $altura; ?>" required>
                                <?php
                                    if (!$callealt_ok)
                                        echo '<p class="alerta">Altura inválida</p>';
                                ?>
                            </div>
                            <div class="col-sm-6 elemento-form mb-2">
                                <label for="provincia">Provincia</label>
                                <select id="provincia" name="provincia" class="form-control" required>
                                    <?php
                                        $provincias_query = mysqli_query($db, "SELECT * FROM provincia ORDER BY nombre ASC;");
                                        while ($provincia = mysqli_fetch_array($provincias_query))
                                            if ($provincia['id_provincia'] == $codProvincia)
                                                echo "<option value='".$provincia['id_provincia']."' selected>".$provincia['nombre']."</option>";
                                            else
                                                echo "<option value='".$provincia['id_provincia']."'>".$provincia['nombre']."</option>";
                                    ?>
                                </select>
                                <?php
                                    if (!$provincia_ok)
                                        echo '<p class="alerta">Ninguna provincia ha sido seleccionada</p>';
                                ?>
                            </div>
                            <div class="col-sm-6 elemento-form mb-2">
                                <label for="ciudad">Ciudad</label>
                                <select id="ciudad" name="ciudad" class="form-control" required>
                                    <?php
                                        $ciudades_query = mysqli_query($db,
                                            "SELECT * FROM ciudad
                                            WHERE fk_provincia =".$codProvincia." ORDER BY nombre ASC;");
                                        while ($ciudad = mysqli_fetch_array($ciudades_query))
                                            if ($ciudad['id_ciudad'] == $codCiudad)
                                                echo "<option value='".$ciudad['id_ciudad']."' selected>".$ciudad['nombre']."</option>";
                                            else
                                                echo "<option value='".$ciudad['id_ciudad']."'>".$ciudad['nombre']."</option>";
                                    ?>
                                </select>
                                <?php
                                    if (!$ciudad_ok)
                                        echo '<p class="alerta">Ninguna ciudad ha sido seleccionada</p>';
                                ?>
                            </div>
                            <div class="text-center my-auto mx-auto">
                                <div class="mt-3">
                                    <input type="hidden" name="confirma" value="si"/>
                                    <button class="btn btn-primary" type="submit">Modificar</button>
                                    <button id="eliminar-evento" class="btn btn-danger ml-3">Eliminar</button>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-lg-6 d-flex flex-column">
                            <div class="contenedor-nivel2 mt-3 col-12">
                                <div class="contenedor-mapa">
                                    <?php
                                        $ubicacion = $evento['calle'].' '.$evento['altura'].', '.$evento['nombre_ciudad'].', '.$evento['nombre_provincia'];
                                    ?>
                                    <iframe class="mapa"
                                        width="400"
                                        height="350"
                                        frameborder="0"
                                        src="https://www.google.com/maps/embed/v1/place?key=AIzaSyBMTPQ8KW_7vtE_nChnfCgM-AsJTSbwQ1k&q=<?php echo urlencode($ubicacion); ?>"
                                        allowfullscreen>
                                    </iframe>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <a class="btn btn-primary" href="felipe.php">Enviar certificados</a>
            </div>
        </div>
    </div>
    <?php require('comun/barra-fondo.php'); ?>
    
    <div id="id_evento" valor="<?php echo $idEvento; ?>" hidden></div>
    <script type="text/javascript" src="/js/manejador-evento.js"></script>
    <script type="text/javascript" src="/js/manejador-ajax.js"></script>
</body>
</html>