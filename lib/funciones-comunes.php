<?php
    // Esta funcion recibe dos strings de fechas
    function compararFechas($datetime1, $datetime2){
        $datetime_obj1 = new DateTime($datetime1);
        $datetime_obj2 = new DateTime($datetime2);
        if ($datetime_obj1 > $datetime_obj2)
            return 1;
        elseif ($datetime_obj1 < $datetime_obj2)
            return -1;
        else
            return 0;
    }
    
    // Esta funcion verifica la imagen subida determinada por selector
    function imagenCorrecta($selector){
        if (!is_uploaded_file($_FILES[$selector]['tmp_name']))
            return true;
        else {
            $chequeo = exif_imagetype($_FILES[$selector]['tmp_name']);
            return ($chequeo == IMAGETYPE_JPEG || $chequeo == IMAGETYPE_PNG) && $_FILES[$selector]['size'] <= 5000000;
        }
    }
?>