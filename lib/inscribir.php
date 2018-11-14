<?php
    require('funcdb.php');
    $db = conectadb();
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
                echo '<tr id="inscripcion-'.$inscripcion['id_inscripcion'].'">
                    <th scope="row">'.$inscripcion['nombre'].'</th>
                    <th scope="row">'.$inscripcion['apellido'].'</th>
                    <th scope="row">'.$inscripcion['email'].'</th>
                    <th scope="row">'.date('Y-m-d', strtotime($inscripcion['fecha_inscripcion'])).'</th>
                    <th scope="row">'.$inscripcion['tipo'].'</th>
                    <th scope="row">'.$inscripcion['asistencia'].'</th>
                    <th scope="row">
                        <button class="eliminar-inscripcion btn btn-danger" valor="'.$inscripcion['id_inscripcion'].'">
                            Borrar
                        </button>
                    </th>
                </tr>';
            }
        }
    }
?>