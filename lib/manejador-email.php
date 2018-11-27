<?php
    /*
    * Install
    * pear install Mail
    * pear install Mail_mime
    * pear install Net_SMTP
    * pear install Auth_SASL2
    * 
    * 
    * 
    * 
    */
    require_once "Mail.php";
    require_once "Mail/mime.php";
    require_once "funcdb.php";

    $db = conectadb();
    if (isset($_REQUEST['idEvento'])){
        $idEvento = $_REQUEST['idEvento'];
        $sql = "SELECT p.nombre, p.apellido, p.email,
            i.id_inscripcion, i.tipo,
            c.nombre_certificado, c.id_certificado
            FROM perfil p
            INNER JOIN inscripcion i ON i.fk_perfil = p.id
            INNER JOIN certificado c ON i.id_inscripcion = c.fk_inscripcion
            WHERE i.fk_evento = $idEvento;";
        if ($result = $db->query($sql, MYSQLI_USE_RESULT)){
            $todos=Array();
            while ($row = $result->fetch_object()){
                array_push($todos,$row);
            }
            $result->free();
            foreach ($todos as $row){
                $id = $row->id_certificado;
                $nombre = ucwords(strtolower($row->nombre));
                $apellido = ucwords(strtolower($row->apellido));
                $apinombre = preg_replace('/\s+/', ' ', $nombre." ".$apellido);
                $apinombre = utf8_decode($apinombre);
                $email = strtolower($row->email);
                $file = $row->nombre_certificado;

                if (enviarEmail($email, $apinombre, $file)){
                    $b_Entregado = true;        
                    $sql = "UPDATE certificado
                        SET entregado = $b_Entregado, email_enviado = '$email'
                        WHERE id_certificado = $id;";
                    $db->query($sql, MYSQLI_USE_RESULT);
                }
            }
        }
    }

    function enviarEmail($email, $apinombre, $file){
        $res;
        $host = "patora.fi.mdp.edu.ar";
        $username = "user";
        $password = "pass";
        $port = "25";

        $email_from = '"Dto. Informatica - FI - UNMdP" <informatica@fi.mdp.edu.ar>';
        $email_subject = "Hola" ;
        $email_body = "Estimado @@NAME@@:

        Felipe definitivamente debería cambiar la contraseña.



        Lic. Carlos A. Rico 
        Director del Departamento
        de ingeniería Informática
        Facultad de Ingeniería
        UNMdP



        " ;
        $email_address = "informatica@fi.mdp.edu.ar";

        $mail = new Mail_mime(array('eol' => "\n"));
        $text = utf8_decode(str_replace('@@NAME@@', $apinombre,$email_body));
        $mail->setTXTBody($text);
        if ($mail->addAttachment("../certificados/".$file,'application/pdf')){
            echo "attached successfully! </br>";
            $headers = array (	'From' => $email_from,
                            'Subject' => $email_subject, 
                            'Reply-To' => $email_address,
                            );
                            
            $body = $mail->get();
            $hdrs = $mail->headers($headers);
            
            $smtp = Mail::factory('smtp', 
                            array ('host' => $host, 
                                    'port' => $port, 
                                    'auth' => true, 
                                    'username' => $username, 
                                    'password' => $password,
                                    'socket_options' => array ('ssl' => array(
                                                            'verify_peer'      => false,
                                                            'verify_peer_name' => false
                                                            )
                                                        )
                                    ));
            $smtp->send($email, $hdrs, $body);

            if (PEAR::isError($mail)){
                echo("" . $mail->getMessage() . "\n");
                $res = false;
            }
            else {
                echo("Message successfully sent!\n");
                $res = true;
            }
        }
        else {
            echo "Nope, failed to attache!! </br>";
            $res = false;
        }
        return $res;
    }
?>