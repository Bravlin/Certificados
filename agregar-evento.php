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
    $fecreal_ok = true;
    $portada_ok = true;
    
    // Procesamiento del formulario
    if (isset($_REQUEST['confirma']) && $_REQUEST['confirma'] == 'si'){
        $nombre = $_REQUEST['nombre'];
        $descripcion = $_REQUEST['descripcion'];
        $calle = $_REQUEST['calle'];
        $callealt = $_REQUEST['callealt'];
        $codProvincia = $_REQUEST['provincia'];
        $codCiudad = $_REQUEST['ciudad'];
        $fecreal = $_REQUEST['fecreal'];
        // Validaciones
        $nombre_ok = $nombre != "";
        $calle_ok = $calle != "";
        $callealt_ok = $callealt > 0;
        $provincia_ok = $codProvincia != "";
        $ciudad_ok = $codCiudad != "";
        $fecreal_ok = compararFechas($fecreal, date("Y-m-d h:i:sa")) > 0;
        $portada_ok = imagenCorrecta('portada');
        if ($nombre_ok && $calle_ok && $callealt_ok && $provincia_ok && $ciudad_ok && $fecreal_ok && $portada_ok){
            $nombre = mysqli_real_escape_string($db, $nombre);
            $descripcion = mysqli_real_escape_string($db, $descripcion);
            $calle = mysqli_real_escape_string($db, $calle);
            $fechaCreac = date("Y-m-d h:i:sa");
            mysqli_query($db,
                "INSERT INTO evento (nombre, fecha_creacion, fecha_realizacion, descripcion, direccion_calle, direccion_altura, fk_ciudad)
                VALUES ('$nombre', '$fechaCreac', '$fecreal', '$descripcion', '$calle', $callealt, $codCiudad);");
            $idEvento = mysqli_insert_id($db);
            if (is_uploaded_file($_FILES['portada']['tmp_name']))
                subirImagen($idEvento);
            header("location: eventos.php");
        }
    }
    
    function subirImagen($idEvento){
        $directorio = "../media/portadas-eventos/";
        $archivo_path = $directorio . $idEvento ."-p";
        if (!move_uploaded_file($_FILES['portada']['tmp_name'], $archivo_path))
            echo '<script language="javascript">alert("Error inesperado al tratar de subir la portada.");</script>'; 
    }
?>

<!DOCTYPE html>
<html>
<head>
    <title>Agregar evento - Eventu</title>
    <?php require('comun/head-navegacion.php'); ?>
    <link rel="stylesheet" type="text/css" href="/css/formulario.css">
</head>
<body>
    <?php require('comun/navbar.php'); ?>
    <div class="container-fluid">
        <div class="row">
            <?php require('comun/barra-vertical.php'); ?>
            <div class="col-12 col-md-9 col-lg-10 py-5 row justify-content-center mx-auto">
                <div class="form-container col-10 col-lg-8 py-5 px-1 px-sm-3">
                    <form class="formulario-principal color-blanco" method="POST" enctype="multipart/form-data">
                        <h1 class="text-center">Incorpora un evento</h1>
                        <input type="hidden" name="confirma" value="si"/>
                        <div class="row cuerpo-form">
                            <div class="col-12 elemento-form">
                                <label for="nombre">Nombre del evento</label>
                                <input id="nombre" name="nombre" type="text" class="form-control" placeholder="Mi evento"
                                value="<?php if(isset($_REQUEST['nombre'])) echo $_REQUEST['nombre']; ?>" required>
                                <?php
                                    if (!$nombre_ok)
                                        echo '<p class="alerta">El nombre no puede quedar vacío</p>';
                                ?>
                            </div>
                            <div class="col-12 elemento-form">
                                <label for="descripcion">Descripción</label>
                                <textarea id="descripcion" name="descripcion" type="text" class="form-control" placeholder="Describa al evento..."><?php if(isset($_REQUEST['descripcion'])) echo $_REQUEST['descripcion']; ?></textarea>
                            </div>
                            <div class="col-sm-12 col-md-6 elemento-form">
                                <label for="calle">Calle</label>
                                <input id="calle" name="calle" type="text" class="form-control" placeholder="Calle"
                                value="<?php if(isset($_REQUEST['calle'])) echo $_REQUEST['calle']; ?>" required>
                                <?php
                                    if (!$calle_ok)
                                        echo '<p class="alerta">La calle no puede quedar vacía</p>';
                                ?>
                            </div>
                            <div class="col-sm-12 col-md-6 elemento-form">
                                <label for="altura">Altura</label>
                                <input id="altura" name="callealt" type="number" class="form-control" placeholder="123"
                                value="<?php if(isset($_REQUEST['callealt'])) echo $_REQUEST['callealt']; ?>" required>
                                <?php
                                    if (!$callealt_ok)
                                        echo '<p class="alerta">Altura inválida</p>';
                                ?>
                            </div>
                            <div class="col-sm-12 col-md-6 elemento-form">
                                <label for="provincia">Provincia</label>
                                <select id="provincia" name="provincia" class="form-control" required>
                                    <option value="">Elija una provincia...</option>
                                    <?php
                                        $provincia_query = mysqli_query($db, "SELECT * FROM provincia ORDER BY nombre ASC;");
                                        while ($provincia = mysqli_fetch_array($provincia_query))
                                            echo "<option value='".$provincia['id_provincia']."'>".$provincia['nombre']."</option>";
                                    ?>
                                </select>
                                <?php
                                    if (!$provincia_ok)
                                        echo '<p class="alerta">Ninguna provincia ha sido seleccionada</p>';
                                ?>
                            </div>
                            <div class="col-sm-12 col-md-6 elemento-form">
                                <label for="ciudad">Ciudad</label>
                                <select id="ciudad" name="ciudad" class="form-control" required>
                                    <option value="">Primero elija una provincia</option>
                                </select>
                                <?php
                                    if (!$ciudad_ok)
                                        echo '<p class="alerta">Ninguna ciudad ha sido seleccionada</p>';
                                ?>
                            </div>
                            <div class="col-sm-12 col-md-6 elemento-form">
                                <label for="fechaReal">Fecha y hora de realización</label>
                                <input id="fechaReal" name="fecreal" type="datetime-local" class="form-control"
                                value="<?php if(isset($_REQUEST['fecreal'])) echo $_REQUEST['fecreal']; ?>" required>
                                <?php
                                    if (!$fecreal_ok)
                                        echo '<p class="alerta">Ingrese una fecha válida</p>';
                                ?>
                            </div>
                            <div class="col-sm-12 col-md-6 elemento-form">
                                <label for="portada">Portada</label>
                                <input id="portada" name="portada" type="file" class="form-control">
                                <?php
                                    if (!$portada_ok)
                                        echo '<p class="alerta">Archivo no válido.</p>';
                                ?>
                            </div>
                        </div>
                        <div class="row justify-content-center">
                            <button class="btn eventuButton" type="submit">Enviar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php require('comun/barra-fondo.php'); ?>
    
    <script type="text/javascript" src="/js/manejador-ajax.js"></script>
</body>
</html>