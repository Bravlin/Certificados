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
    $organismo_ok = true;
    $cargo_ok = true;
    
    // Procesamiento del formulario
    if (isset($_REQUEST['confirma']) && $_REQUEST['confirma'] == 'si'){
        $nombres = $_REQUEST['nombres'];
        $apellidos = $_REQUEST['apellidos'];
        $telefono = $_REQUEST['telefono'];
        $email = $_REQUEST['email'];
        $organismo = $_REQUEST['organismo'];
        $cargo = $_REQUEST['cargo'];
        // Validaciones
        $nombres_ok = $nombres != '';
        $apellidos_ok = $apellidos != '';
        $telefono_ok = $telefono != '';
        $email_ok = validaEmail($email, $db);
        $organismo_ok = $organismo != '';
        $cargo_ok = $cargo != '';
        if ($nombres_ok && $apellidos_ok && $telefono_ok && $email_ok && $organismo_ok && $cargo_ok){
            $nombres = mysqli_real_escape_string($db, $nombres);
            $apellidos = mysqli_real_escape_string($db, $apellidos);
            $organismo = mysqli_real_escape_string($db, $organismo);
            $cargo = mysqli_real_escape_string($db, $cargo);
            mysqli_query($db, "INSERT INTO perfil (nombre, apellido, telefono, email, organismo, cargo)
                VALUES ('$nombres', '$apellidos', '$telefono', '$email', '$organismo', '$cargo');");
            header("location: perfiles.php");
        }
    }

    function validaEmail($email, $db){
        if (filter_var($email, FILTER_VALIDATE_EMAIL)){
            $query_verificacion = mysqli_query($db, "SELECT * FROM perfil WHERE email = '$email';");
            return mysqli_num_rows($query_verificacion) == 0;
        }
        else
            return false;
    }
?>

<!DOCTYPE html>
<html>
<head>
    <title>Agregar perfil - FICertif</title>
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
                        <h1 class="text-center">Registrar perfil</h1>
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
                                <label for="telefono">Teléfono</label>
                                <input id="telefono" name="telefono" type="tel" class="form-control" placeholder="+54..."
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
                                        echo '<p class="alerta">El email es inválido o ya existe.</p>';
                                ?>
                            </div>
                            <div class="col-sm-12 col-md-6 elemento-form">
                                <label for="organismo">Organismo</label>
                                <input id="organismo" name="organismo" type="text" class="form-control" placeholder="Organismo" 
                                value="<?php if(isset($_REQUEST['organismo'])) echo $_REQUEST['organismo']; ?>" required>
                                <?php
                                    if (!$organismo_ok)
                                        echo '<p class="alerta">Debe indicar un organismo.</p>';
                                ?>
                            </div>
                            <div class="col-sm-12 col-md-6 elemento-form">
                                <label for="cargo">Cargo</label>
                                <input id="cargo" name="cargo" type="text" class="form-control" placeholder="Cargo" 
                                value="<?php if(isset($_REQUEST['cargo'])) echo $_REQUEST['cargo']; ?>" required>
                                <?php
                                    if (!$cargo_ok)
                                        echo '<p class="alerta">Debe indicar un cargo.</p>';
                                ?>
                            </div>
                        </div>
                        <div class="row justify-content-center">
                            <button id="enviar" class="btn ficertifButton" type="submit">Registrar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php require('comun/barra-fondo.php'); ?>
</body>
</html>