<?php
    require('funcdb.php');
    $db = conectadb();
    switch ($_REQUEST['accion']){
        case "A":
            inscribir($db);
            break;
        case "B":
            desinscribir($db);
            break;
        case "M":
            actualizar($db);
    }

    function inscribir($db){
        $idEvento = (isset($_REQUEST['idEvento'])) ? $_REQUEST['idEvento'] : "";
        $idPerfil = (isset($_REQUEST['idPerfil'])) ? $_REQUEST['idPerfil'] : "";
        $tipo = (isset($_REQUEST['tipo'])) ? $_REQUEST['tipo'] : "";
        if ($idEvento != "" && $idPerfil != "" && $tipo != ""){
            mysqli_query($db, 
                "INSERT INTO inscripcion (tipo, fk_perfil, fk_evento)
                VALUES ('$tipo', $idPerfil, $idEvento)");
            $idInscrip = mysqli_insert_id($db);
            if ($idInscrip){
                $inscripcion_query = mysqli_query($db,
                    "SELECT p.id AS id_perfil, p.nombre, p.apellido, p.email,
                    i.id_inscripcion, i.tipo, i.fecha_inscripcion, i.asistencia
                    FROM perfil p
                    INNER JOIN inscripcion i ON i.fk_perfil = p.id
                    WHERE i.id_inscripcion = $idInscrip;");
                if($inscripcion_query){
                    $inscripcion = mysqli_fetch_array($inscripcion_query);
                    $idInscripcion = $inscripcion['id_inscripcion'];
                    $select_asistencia = '<select class="select-asistencia form-control" valor="'.$idInscripcion.'">
                        <option value="">S/D</option>
                        <option value="1">Sí</option>
                        <option value="0">No</option>
                    </select>';
                    echo '<tr id="inscripcion-'.$idInscripcion.'">
                        <td>'.$inscripcion['nombre'].'</td>
                        <td>'.$inscripcion['apellido'].'</td>
                        <td>'.$inscripcion['email'].'</td>
                        <td>'.date('Y-m-d', strtotime($inscripcion['fecha_inscripcion'])).'</td>
                        <td>'.$inscripcion['tipo'].'</td>
                        <td>'.$select_asistencia.'</td>
                        <td>
                            <a class="btn btn-primary" href="modificar-perfil.php?idPerfil='.$inscripcion['id_perfil'].'">
                                Modificar
                            </a>
                        </td>
                        <td>
                            <button id="emitir-i'.$idInscripcion.'" class="email-ind btn btn-success" valor="'.$idInscripcion.'"
                            type="button" data-toggle="modal" data-target="#modal-mail" disabled>
                                Enviar cert.
                            </button>
                        </td>
                        <td>
                            <button class="eliminar-inscripcion btn btn-danger" valor="'.$idInscripcion.'">
                                Borrar
                            </button>
                        </td>
                    </tr>';
                }
            }
        }
    }

    function desinscribir($db){
        $idInscrip = $_REQUEST['idInscrip'];
        mysqli_query($db, "DELETE FROM inscripcion WHERE id_inscripcion = $idInscrip;");
    }

    function actualizar($db){
        $idInscrip = $_REQUEST['idInscrip'];
        $asistencia = ($_REQUEST['asistencia'] != "") ? $_REQUEST['asistencia'] : "NULL";
        mysqli_query($db,
            "UPDATE inscripcion
            SET asistencia = $asistencia
            WHERE id_inscripcion = $idInscrip;");
    }
?>