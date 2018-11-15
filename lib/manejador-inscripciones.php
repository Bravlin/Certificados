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
        $tipos = array("Asistente", "Evaluador", "Organizador", "Disertante");
        $idEvento = (isset($_REQUEST['idEvento'])) ? $_REQUEST['idEvento'] : "";
        $idPerfil = (isset($_REQUEST['idPerfil'])) ? $_REQUEST['idPerfil'] : "";
        $tipo = (isset($_REQUEST['tipo'])) ? $_REQUEST['tipo'] : "";
        if ($idEvento != "" && $idPerfil != "" && in_array($tipo, $tipos)){
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
                        <th scope="row">'.$inscripcion['nombre'].'</th>
                        <th scope="row">'.$inscripcion['apellido'].'</th>
                        <th scope="row">'.$inscripcion['email'].'</th>
                        <th scope="row">'.date('Y-m-d', strtotime($inscripcion['fecha_inscripcion'])).'</th>
                        <th scope="row">'.$inscripcion['tipo'].'</th>
                        <th scope="row">'.$select_asistencia.'</th>
                        <th scope="row">
                            <button class="eliminar-inscripcion btn btn-danger" valor="'.$idInscripcion.'">
                                Borrar
                            </button>
                        </th>
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