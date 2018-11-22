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
    $args = array(  array('filter'    =>FILTER_SANITIZE_STRING ),
                    array('filter'    =>FILTER_SANITIZE_STRING ),
                    array('filter'    =>FILTER_SANITIZE_STRING ),
                    array('filter'    =>FILTER_SANITIZE_EMAIL ),
                    array('filter'    =>FILTER_SANITIZE_STRING ),
                    array('filter'    =>FILTER_SANITIZE_STRING )
                  );

  // Procesamiento del formulario


  if (isset($_REQUEST['confirma']) && $_REQUEST['confirma'] == 'si'){
            echo "Procesando";
            $target_dir = "tmp/";
            $target_file = $target_dir. basename($_FILES["csvFile"]["name"]);
            $fileType = pathinfo($target_file,PATHINFO_EXTENSION);
            echo "-- $target_file -- ".$_FILES["csvFile"]["tmp_name"];

            // Allow certain file formats
            if($fileType != "csv" ) {
              echo "Sorry, only CSV files are allowed. ";
            }
            else {
            if (move_uploaded_file($_FILES["csvFile"]["tmp_name"], $target_file)) {
              echo "The file ". basename( $_FILES["csvFile"]["name"]). " has been uploaded.";
            } else {
              echo "Sorry, there was an error uploading your file.";
            }

            $fila = 1;
            if (($gestor = fopen($target_file, "r")) !== FALSE) {
              while (($datos = fgetcsv($gestor, 1000, ";")) !== FALSE) {
                $numero = count($datos);
                echo "<p> $numero de campos en la línea $fila: <br /></p>\n";
                $fila++;
                if (($numero == 6) || ($numero == 7)) {
                  //if (filter_var_array($datos,$args)){
                  //if (filter_var(trim($datos[3]), FILTER_SANITIZE_EMAIL)) {
                  if ( superValidateEmail(trim($datos[3]))) {
                    $nombres = $datos[0];
                    $apellidos = $datos[1];
                    $telefono =  $datos[2];
                    $email = $datos[3];
                    $organismo =  $datos[4];
                    $cargo =  $datos[5];
                    // Validaciones

                    $nombres_ok = $nombres != '';
                    $apellidos_ok = $apellidos != '';
                    $telefono_ok = $telefono != '';
                    $email_ok = $email != '';
                    $organismo_ok = $organismo != '';
                    $cargo_ok = $cargo != '';
                    if ($nombres_ok && $apellidos_ok && $telefono_ok && $email_ok && $organismo_ok && $cargo_ok){
                        $nombres = mysqli_real_escape_string($db, $nombres);
                        $apellidos = mysqli_real_escape_string($db, $apellidos);
                        $organismo = mysqli_real_escape_string($db, $organismo);
                        $cargo = mysqli_real_escape_string($db, $cargo);
                        mysqli_begin_transaction($db);
                        $sql = "INSERT INTO perfil (nombre, apellido, telefono, email, organismo, cargo)
                            VALUES ('$nombres', '$apellidos', '$telefono', '$email', '$organismo', '$cargo')
                                ON DUPLICATE KEY UPDATE
                                  nombre = '$nombres', apellido = '$apellidos', telefono = '$telefono', organismo = '$organismo', cargo = '$cargo';
                            ";
                        echo $sql;
                        mysqli_query($db, $sql);
                        var_dump($_REQUEST);

                        $selectEvento = mysqli_real_escape_string($db,$_REQUEST['select-evento']);
                        if (isset($selectEvento) && (!empty($selectEvento ))) {
                            $selectTipo = mysqli_real_escape_string($db,$_REQUEST['select-tipo']);
                            $selectAsistencia = mysqli_real_escape_string($db,$_REQUEST['select-asistencia']);
                            if ($selectAsistencia == "Presente")  {
                              $asis = 1;
                            } else {
                              $asis = 0;
                            }
                            $sql = "SELECT id FROM perfil WHERE email = $email_ok";
                            echo $sql;

                            $result = mysqli_query($db,$sql);
                            $row = mysqli_fetch_assoc($result);
                            if (isset($row["id"]) && (is_int($row["id"]))) {
                              $sql = "INSERT INTO `inscripcion`( `tipo`, `asistencia`, `fk_perfil`, `fk_evento`)
                                VALUES ('".$selectTipo."',".$asis.",".$row["id"].",".$selectEvento.")";
                              echo $sql;

                              $result = mysqli_query($db, "INSERT INTO `inscripcion`( `tipo`, `asistencia`, `fk_perfil`, `fk_evento`)
                                VALUES ('".$selectTipo."',".$asis.",".$row["id"].",".$selectEvento.")");
                              }
                            }
                            if (mysqli_commit($db)) {
                              mysqli_rollback($db);
                            }
                            mysqli_close($db);
                            exit;
                        }

                  } else {
                      echo "Error en el tipo de datos";
                  }
                }
              }
              fclose($gestor);
              header("location: perfiles.php");
            }
            exit;


        }
      }



    function validaEmail($email, $db){
        if (filter_var($email, FILTER_VALIDATE_EMAIL)){
            $query_verificacion = mysqli_query($db, "SELECT *
                FROM perfil
                WHERE id <> $idPerfil AND email = '$email';");
            return mysqli_num_rows($query_verificacion) == 0;
        }
        else
            return false;
    }
    function superValidateEmail($email)
    {
        // SET INITIAL RETURN VARIABLES

            $emailIsValid = FALSE;

        // MAKE SURE AN EMPTY STRING WASN'T PASSED

            if (!empty($email))
            {
                // GET EMAIL PARTS

                    $domain = ltrim(stristr($email, '@'), '@') . '.';
                    $user   = stristr($email, '@', TRUE);

                // VALIDATE EMAIL ADDRESS

                    if
                    (
                        !empty($user) &&
                        !empty($domain) &&
                        checkdnsrr($domain)
                    )
                    {$emailIsValid = TRUE;}
            }

        // RETURN RESULT

            return $emailIsValid;
    }



?>

<!DOCTYPE html>
<html>
<head>
    <title>Agregar varios - FICertif</title>
    <?php require('comun/head-navegacion.php'); ?>
    <link rel="stylesheet" type="text/css" href="<?php echo URL; ?>/css/formulario.css">
</head>
<body>
    <?php require('comun/navbar.php'); ?>
    <div class="container-fluid">
        <div class="row">
            <?php require('comun/barra-vertical.php'); ?>
            <div class="col-12 col-md-9 col-lg-10 py-5 row justify-content-center mx-auto">
                <div class="form-container col-10 col-lg-8 py-5 px-1 px-sm-3">
                    <form class="formulario-principal color-blanco" method="POST" enctype="multipart/form-data">
                        <h1 class="text-center">Agregar Varios</h1>
                        <input type="hidden" name="confirma" value="si"/>
                        <div class="row cuerpo-form">
                            <div class="col-sm-12 col-md-12 elemento-form">
                              <div class="custom-file">
                                <input name="csvFile" type="file" class="custom-file-input" id="customFile" >
                                    <label class="custom-file-label" for="customFile">Elija un Archivo CSV</label>
                                </div>
                            </div>
                        </div>
                        <div class="row my-4">
                            <div class="col-12 col-sm-4">
                                <select id="select-evento" name="select-evento" class="form-control" required>
                                    <option value="">Elija a quien inscribir...</option>
                                    <?php
                                        $perfil_query = mysqli_query($db,
                                            "SELECT id_evento, nombre
                                            FROM evento
                                            ORDER BY nombre ASC;");
                                        while ($perfil = mysqli_fetch_array($perfil_query))
                                            echo "<option value='".$perfil['id_evento']."'>".
                                                $perfil['nombre']." ".
                                                "</option>";
                                    ?>
                                </select>
                            </div>
                            <div class="col-12 col-sm-4">
                                <select id="select-tipo" name="select-tipo" class="form-control" required>
                                    <option value="">Participa como...</option>
                                    <option value="Asistente">Asistente</option>
                                    <option value="Evaluador">Evaluador</option>
                                    <option value="Organizador">Organizador</option>
                                    <option value="Disertante">Disertante</option>
                                </select>
                            </div>
                            <div class="col-12 col-sm-4">
                                    <select id="select-asistencia" name="select-asistencia" class="form-control" required>
                                        <option value="Presente">Presente</option>
                                        <option value="Ausente">Ausente</option>
                                    </select>
                            </div>

                          </div>


                        <div class="row justify-content-center">
                            <button id="enviar" class="btn ficertifButton" type="submit">Enviar archivo</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php require('comun/barra-fondo.php'); ?>
</body>
</html>
