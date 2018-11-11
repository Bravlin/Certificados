<?php
    $requiere_sesion = false;
    require('lib/sesion-redireccion.php');
    require('lib/funcdb.php');
    $msg_error = -1;
    $db = conectadb();
    if ($db){
        if (isset($_REQUEST['confirma']) && $_REQUEST['confirma'] == 'si'){
            $email = $_REQUEST['email'];
            $contrasena = $_REQUEST['contrasena'];
            $contrasena = md5($contrasena);
            $usuario_query = mysqli_query($db,
                "SELECT A.id_administrador, A.email, A.password
                FROM administrador A
                WHERE A.email = '$email' AND A.password = '$contrasena';");
            if (mysqli_num_rows($usuario_query) == 1){
                $usuario = mysqli_fetch_array($usuario_query);
                $_SESSION['id_administrador'] = $usuario['id_administrador'];
                header("location: index.php");
                session_write_close();
            }
            else
                $msg_error = 1;
        }
    }
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login - Eventu</title>
    <?php require('comun/head-comun.php'); ?>
    <link rel="stylesheet" type="text/css" href="css/login.css">
</head>
<body>
    <header>
        <div class="container-fluid text-center">
            <a class="logo mb-5 mt-0" href="index.php">
                <img src="src/imagenes/logo.svg">Eventu
            </a>
        </div>
    </header>
    <div class="contenedor-pagina row justify-content-center py-5 mx-0">
        <div class="form-container col-10 col-sm-8 col-md-6">
            <form class="text-center py-5 px-1 px-sm-3 login" method="POST">
                <h1>Administración</h1>
                <input type="hidden" name="confirma" value="si"/>
                <div class="row justify-content-center">
                    <div class="col-12 row justify-content-center">
                        <div class="col-12 col-sm-9 col-md-6 mb-3">
                            <input type="email" id="email" name="email" class="form-control" placeholder="Email o nombre de usuario" required>
                        </div>
                    </div>
                    <div class="col-12 row justify-content-center">
                        <div class="col-12 col-sm-9 col-md-6 mb-3">
                            <input type="password" id="contrasena" name="contrasena" class="form-control" placeholder="Contraseña" required>
                        </div>
                    </div>
                    <?php
                        if ($msg_error == 1)
                            echo '<p class="alerta col-12">Email o contraseña inválidos, intente nuevamente</p>';
                    ?>
                    <div class="col-12 col-lg-8 col-xl-6 row justify-content-center">
                        <div class="col-12 col-lg-6">
                            <label>
                                <input type="checkbox" value="remember-me"> Recordarme
                            </label>
                        </div>
                        <div class="col-12 col-lg-6">
                            <button class="btn eventuButton ml-lg-4" type="submit">Acceder</button>
                        </div>
                    </div>
                    <p class="mt-3 col-12"><a href="">¿Olvidaste tu contraseña?</a></p>
                    <p class="col-12"><a href="registro.php">Registrate</a></p>
                </div>
            </form>
        </div>
    </div>
    <?php require('comun/footer-simple.php'); ?>
</body>
</html>