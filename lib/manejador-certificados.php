<?php
    define("URL","http://www3.fi.mdp.edu.ar/cibercrimen/verificacion.php?verif=");

    set_include_path(".");
    use setasign\Fpdi\Fpdi;

    require_once('fpdf.php');
    require_once('fpdi/autoload.php');
    require_once "funcdb.php";
    require_once('phpqrcode/qrlib.php');

    $db = conectadb();
    if (isset($_REQUEST['idEvento'])){
        $idEvento = $_REQUEST['idEvento'];
        $sql = "SELECT p.nombre, p.apellido, p.email,
            i.id_inscripcion, i.tipo
            FROM perfil p
            INNER JOIN inscripcion i ON i.fk_perfil = p.id
            WHERE i.fk_evento = $idEvento AND i.asistencia = 1;";
        if ($result = $db->query($sql, MYSQLI_USE_RESULT)){
            $todos=Array();
            while($row = $result->fetch_object()){
                array_push($todos,$row);
            }
            $result->free();
            foreach ($todos as $row){
                $id = $row->id_inscripcion;
                //$nombre = utf8_decode(ucwords(mb_strtolower($row->nombre, 'UTF-8')));
                //$apellido = utf8_decode(ucwords(mb_strtolower($row->apellido, 'UTF-8')));
                $nombre = ucwords(strtolower($row->nombre));
                $apellido = ucwords(strtolower($row->apellido));
                $apinombre = preg_replace('/\s+/', ' ', $nombre." ".$apellido);
                $apinombre = utf8_decode($apinombre);
                
                /*if (mb_detect_encoding($apinombre) == "ASCII"){
                    $apinombre = utf8_encode($apinombre);
                }*/
                
                $email = strtolower($row->email);

                //if($result2  = $link->query("select count(*) as count from certificado where id=".$id." AND Entregado IS  TRUE", MYSQLI_USE_RESULT)) {
                if($result2  = $db->query("SELECT COUNT(*) AS count FROM certificado WHERE fk_inscripcion = $id;", MYSQLI_USE_RESULT)) {
                    $row = $result2->fetch_object();
                    $result2->free();
                    //if ($row->count == 0) {
                    if ($row->count == 0) {
                        $param= $id.rand(1000,9999);
                        $param=str_pad($param, 8, '0', STR_PAD_LEFT);

                        $pdf = genPdf($apinombre,$param);
                        $file ="Certificado_jornada_cibercrimen_". utf8_decode(str_replace(' ', '_',$apinombre));
                        $file = preg_replace("/[^a-zA-Z0-9\_\-]+/", "_", $file);
                        $file = $file.".pdf";
                        
                        $pdf->Output('F',"../tmp/".$file);
                        $pdf->Output('F',"../certificados/".$file);

                        $b_id = $id;  
                        $b_nombreCertificado = $file;
                        $b_Aleatorio = $param;
                            
                        $sql = "INSERT INTO certificado (fk_inscripcion, nombre_certificado, fecha_emision, aleatorio) 
                            VALUES ($b_id, '$b_nombreCertificado', NOW(), '$b_Aleatorio')
                            ON DUPLICATE KEY UPDATE  fk_inscripcion = $b_id,
                            nombre_certificado = '$b_nombreCertificado',
                            aleatorio = '$b_Aleatorio';";
                        $db->query($sql, MYSQLI_USE_RESULT);
                    }
                }
            }
        }
    }

    /*
    *  pdf->output 
    *   I: send the file inline to the browser. The PDF viewer is used if available.
    *   D: send to the browser and force a file download with the name given by name.
    *   F: save to a local file with the name given by name (may include a path).
    *   S: return the document as a string.

    * 
    * 
    */

    function genPdf($nombre,$param) {
        // initiate FPDI
        $pdf = new Fpdi('L','mm','A4');;
        // add a page
        $pdf->AddPage();
        // set the source file
        $pdf->setSourceFile('../Template/CERT. asistencia ciberdefensa.pdf');
        // import page 1
        $tplIdx = $pdf->importPage(1);
        // use the imported page and place it at position 10,10 with a width of 100 mm
        $pdf->useTemplate($tplIdx);

        // now write some text above the imported page
        $pdf->SetFont('Helvetica','B',24);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetXY(140 - $pdf->GetStringWidth($nombre)/2, 100);
        $pdf->Write(0, $nombre);

        // generación del qr
        $codeText = URL.$param;

        $l = $pdf->AddLink();
            $pdf->SetLink($l, 0, $codeText);

        $img = gr_generator($codeText);
        $pic = 'data://text/plain;base64,' . base64_encode(($img));
        $pdf->Image($pic,118,152,45,45,'PNG',$codeText);
        
        $pdf->SetTextColor(0,0,255);
        $pdf->SetFont('Arial','B',12);
        $text = "Codigo Verificaci".hex2bin("c3b3")."n: ".$param;
        echo mb_detect_encoding($text)."<br>";
        if (mb_detect_encoding($text) == "ASCII"){
            $text = utf8_encode($text);
        }

        $pdf->SetXY(140 - $pdf->GetStringWidth($text)/2, 145);
        $pdf->Write(0, utf8_decode($text),$codeText);

        return $pdf;
    }

    function gr_generator($url) {
        $codeContents = $url;
        $outerFrame = 4;
        $pixelPerPoint = 5;
        $jpegQuality = 95;
        
        // generating frame
        $frame = QRcode::text($codeContents, false, QR_ECLEVEL_M);
        
        // rendering frame with GD2 (that should be function by real impl.!!!)
        $h = count($frame);
        $w = strlen($frame[0]);
        
        $imgW = $w + 2*$outerFrame;
        $imgH = $h + 2*$outerFrame;
        
        $base_image = imagecreate($imgW, $imgH);
        $logo = imagecreatefrompng("../img/logofi2.png");
    
        $h_logo = count($logo);
        $w_logo = strlen($logo[0]);
        
        $imgW_logo = $w_logo + 2*$outerFrame;
        $imgH_logo = $h_logo + 2*$outerFrame;
        
        $col[0] = imagecolorallocate($base_image,255,255,255); // BG, white 
        $col[1] = imagecolorallocate($base_image,0,0,255);     // FG, blue
    
        imagefill($base_image, 0, 0, $col[0]);
    
        for($y=0; $y<$h; $y++) {
            for($x=0; $x<$w; $x++) {
                if ($frame[$y][$x] == '1') {
                    imagesetpixel($base_image,$x+$outerFrame,$y+$outerFrame,$col[1]); 
                }
            }
        }
        
        // saving to file
        $target_image = imagecreate($imgW * $pixelPerPoint, $imgH * $pixelPerPoint);
        imagecopyresized(
            $target_image, 
            $base_image, 
            0, 0, 0, 0, 
            $imgW * $pixelPerPoint, $imgH * $pixelPerPoint, $imgW, $imgH
        );
    
        $x_target = imagesx($target_image);
        $y_target = imagesy($target_image);
        
        $x_logo = imagesx($logo);
        $y_logo = imagesy($logo);
    
        $target_logo = imagecreate(intval($x_target/4*1.2), intval($y_target/4));
        imagecopyresized(
            $target_logo, 
            $logo, 
            0, 0, 0, 0, 
            intval($x_target/4*1.2) , intval($y_target/4), $x_logo, $y_logo
        );
    
        echo ($x_target." ".$y_target." ".$x_logo." ".$y_logo." -- " );
    
        imagecopy($target_image, $target_logo, intval(($x_target/2)-(imagesx($target_logo)/2)), intval($y_target/2)-((imagesy($target_logo)/2)), 0, 0,  imagesx($target_logo), imagesy($target_logo));
    
        imagedestroy($base_image);
        imagedestroy($logo);
        ob_start();
        imagepng($target_image);
        imagepng($target_logo);
        $imagedata = ob_get_contents();
        ob_end_clean();
        imagedestroy($target_image);
        return $imagedata;
    }
?>