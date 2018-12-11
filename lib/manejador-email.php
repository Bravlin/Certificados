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

    if (isset($_REQUEST['accion']))
        switch ($_REQUEST['accion']){
            case 'T':
                mandarTodos($db);
                break;
            case 'I':
                if (isset($_REQUEST['idInscrip'])){
                    $idInscrip = $_REQUEST['idInscrip'];
                    $sql = "SELECT p.nombre, p.apellido, p.email,
                        i.id_inscripcion, i.tipo,
                        c.nombre_certificado, c.id_certificado
                        FROM perfil p
                        INNER JOIN inscripcion i ON i.fk_perfil = p.id
                        INNER JOIN certificado c ON i.id_inscripcion = c.fk_inscripcion
                        WHERE i.id_inscripcion = $idInscrip;";
                    if ($result = $db->query($sql, MYSQLI_USE_RESULT)){
                        $row = $result->fetch_object();
                        $result->free();
                        mandarIndividual($db, $row);
                    }
                }
                break;
        }

    function mandarTodos($db){
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
                foreach ($todos as $row)
                    mandarIndividual($db, $row);
            }
        }
    }

    function mandarIndividual($db, $row){
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

    function enviarEmail($email, $apinombre, $file){
        $host = "patora.fi.mdp.edu.ar";
        $username = "user";
        $password = "pass";
        $port = "25";

        $email_from = '"Dto. Informatica - FI - UNMdP" <'.$_REQUEST['remitente'].'>';
        $email_subject = $_REQUEST['asunto'];
        $email_body = $_REQUEST['cuerpo-mail'];
        $email_address = $_REQUEST['remitente'];

        $mail = new Mail_mime(array('eol' => "\n"));
        $text = utf8_decode(str_replace('@@NAME@@', $apinombre, $email_body));
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
                return false;
            }
            else {
                echo("Message successfully sent!\n");
                return true;
            }
        }
        else {
            echo "Nope, failed to attache!! </br>";
            return false;
        }
    }
?>