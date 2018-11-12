<?php
    $requiere_sesion = true;
    require('lib/sesion-redireccion.php');
    require('lib/funcdb.php');
    $db = conectadb();

    // Verificadores
    $nombres_ok = true;
    $apellidos_ok = true;
    $telefono_ok = true;
    $email_ok = true;
    $facultad_ok = true;
    $universidad_ok = true;
    
    // Procesamiento del formulario
    if (isset($_REQUEST['confirma']) && $_REQUEST['confirma'] == 'si'){
        $nombres = $_REQUEST['nombres'];
        $apellidos = $_REQUEST['apellidos'];
        $telefono = $_REQUEST['telefono'];
        $email = $_REQUEST['email'];
        $facultad = $_REQUEST['facultad'];
        $universidad = $_REQUEST['universidad'];
        // Validaciones
        $nombres_ok = $nombres != '';
        $apellidos_ok = $apellidos != '';
        $telefono_ok = $telefono != '';
        $email_ok = filter_var($email, FILTER_VALIDATE_EMAIL);
        $facultad_ok = $facultad != '';
        $universidad_ok = $universidad != '';
        if ($nombres_ok && $apellidos_ok && $telefono_ok && $email_ok && $facultad_ok && $universidad_ok){
            $nombres = mysqli_real_escape_string($db, $nombres);
            $apellidos = mysqli_real_escape_string($db, $apellidos);
            $facultad = mysqli_real_escape_string($db, $facultad);
            $universidad = mysqli_real_escape_string($db, $universidad);
            $fecha = date("Y-m-d");
            echo "INSERT INTO ptds (fecha, nombre, apellido, telefono, email, facultad, universidad)
            VALUES ('$fecha', '$nombres', '$apellidos', '$telefono', '$email', '$facultad', '$universidad');";
            echo "INSERT INTO ptds (Fecha, Nombre, Apellido, Telefono, Email, Organismo)
            VALUES ('$fecha', '$nombres', '$apellidos', '$telefono', '$email', '$universidad');";
            mysqli_query($db, "INSERT INTO ptds (fecha, nombre, apellido, telefono, email, facultad, universidad)
                VALUES ('$fecha', '$nombres', '$apellidos', '$telefono', '$email', '$facultad', '$universidad');");
            mysqli_query($db, "INSERT INTO asistencia (Fecha, Nombre, Apellido, Telefono, Email, Organismo)
                VALUES ('$fecha', '$nombres', '$apellidos', '$telefono', '$email', '$universidad');");
            header("location: participantes.php");
        }
    }
?>

<!DOCTYPE html>
<html>
<head>
    <title>Agregar participante - Eventu</title>
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
                        <h1 class="text-center">Inscribir participante</h1>
                        <input type="hidden" name="confirma" value="si"/>
                        <div class="row cuerpo-form">
                            <div class="col-sm-12 col-md-6 elemento-form">
                                <label for="nombres">Nombres</label>
                                <input id="nombres" name="nombres" type="text" class="form-control" placeholder="Juan Martín"
                                value="<?php if(isset($_REQUEST['nombres'])) echo $_REQUEST['nombres']; ?>" required>
                                <?php
                                    if (!$nombres_ok)
                                        echo '<p class="alerta">El nombre no puede quedar vacío</p>';
                                ?>
                            </div>
                            <div class="col-sm-12 col-md-6 elemento-form">
                                <label for="apellidos">Apellidos</label>
                                <input id="apellidos" name="apellidos" type="text" class="form-control" placeholder="Pérez González"
                                value="<?php if(isset($_REQUEST['apellidos'])) echo $_REQUEST['apellidos']; ?>" required>
                                <?php
                                    if (!$apellidos_ok)
                                        echo '<p class="alerta">El apellido no puede quedar vacío</p>';
                                ?>
                            </div>
                            <div class="col-sm-12 col-md-6 elemento-form">
                                <label for="fechaNac">Teléfono</label>
                                <input id="fechaNac" name="telefono" type="tel" class="form-control" placeholder="+54..."
                                value="<?php if(isset($_REQUEST['telefono'])) echo $_REQUEST['telefono']; ?>" required>
                                <?php
                                    if (!$telefono_ok)
                                        echo '<p class="alerta">Ingrese un teléfono válido.</p>';
                                ?>
                            </div>
                            <div class="col-sm-12 col-md-6 elemento-form">
                                <label for="email">E-mail</label>
                                <input id="email" name="email" type="email" class="form-control" placeholder="xxx@xxx.xxx"
                                value="<?php if(isset($_REQUEST['email'])) echo $_REQUEST['email']; ?>" required>
                                <?php
                                    if (!$email_ok)
                                        echo '<p class="alerta">Email inválido</p>';
                                ?>
                            </div>
                            <div class="col-sm-12 col-md-6 elemento-form">
                                <label for="facultad">Facultad</label>
                                <input id="facultad" name="facultad" type="text" class="form-control" placeholder="Facultad" 
                                value="<?php if(isset($_REQUEST['facultad'])) echo $_REQUEST['facultad']; ?>" required>
                                <?php
                                    if (!$facultad_ok)
                                        echo '<p class="alerta">Debe indicar una facultad.</p>';
                                ?>
                            </div>
                            <div class="col-sm-12 col-md-6 elemento-form">
                                <label for="universidad">Universidad</label>
                                <input id="universidad" name="universidad" type="text" class="form-control" placeholder="Universidad" 
                                value="<?php if(isset($_REQUEST['universidad'])) echo $_REQUEST['universidad']; ?>" required>
                                <?php
                                    if (!$universidad_ok)
                                        echo '<p class="alerta">Debe indicar una universidad.</p>';
                                ?>
                            </div>
                        </div>
                        <div class="row justify-content-center">
                            <button id="enviar" class="btn eventuButton" type="submit">Inscribir</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php require('comun/barra-fondo.php'); ?>
</body>
</html>