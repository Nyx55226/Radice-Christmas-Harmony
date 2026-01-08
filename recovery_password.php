<?php
    session_start();
    $conn = new mysqli("localhost","root","","Sql1868450_1");
    require 'PHPMailer/src/PHPMailer.php';
    require 'PHPMailer/src/SMTP.php';
    require 'PHPMailer/src/Exception.php';

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    if(isset($_POST["email"])){
        $email=$_POST["email"];
        
        if(!str_ends_with($email,"@isradice.edu.it")){
            exit;
        }
        $query="SELECT * FROM utenti where email='$email'";

        $res=$conn->query($query);
        if($res->num_rows>0){
            $_SESSION["email"]=$email;
            try {
                $mail = new PHPMailer(true);
                $mail->isMail();
            
                $mail->CharSet = 'UTF-8';
                $mail->setFrom(
                '',
                'Radice Christmas Harmony'
                );
                $mail->isHTML(true);
                $mail->addAddress($email);
                $mail->Subject = 'Recupero Password';
                $mail->Body = '<p>Hai richiesto di reimpostare la password per il tuo account.</p>
                <p>Clicca qui per reimpostarla: <a href="http://localhost/dashboard/radice-christmas-harmony/recovery_password.html">Recupera la password</a></p>
                <p>Se non hai richiesto questa operazione, ignora questa email.</p>
                <p>Grazie,<br>Il team del Benedetto Radice</p>';
                $mail->send();
                $_SESSION["token"]=rand(000,999);
                echo "ok";
        } catch (Exception $e) {
            echo "Errore invio email: {$mail->ErrorInfo}";
        }
        }
    }
    if(isset($_POST["password1"], $_POST["password2"])){
        if(!isset($_SESSION["token"])){
            echo "Sessione scaduta";
            exit;
        }
        $password1=$_POST["password1"];
        $password2=$_POST["password2"];

        if(!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/', $password1)){
            echo "Password non valida";
            exit;
        }
        $password=password_hash($password1, PASSWORD_DEFAULT);
        $email=$_SESSION["email"];
        $query="UPDATE utenti set password='$password' where email='$email'";

        if($conn->query($query)){
            try {
                $mail = new PHPMailer(true);
                $mail->isMail();
            
                $mail->CharSet = 'UTF-8';
                $mail->setFrom(
                '',
                'Radice Christmas Harmony'
                );
                $mail->isHTML(true);
                $mail->addAddress($email);
                $mail->Subject = 'Recupero Password';
                $mail->Body = "<p>La tua password è stata cambiata con successo.</p>
                <p>Se sei stato tu a effettuare questa modifica, puoi ignorare questa email.</p>
                <p>Se non hai richiesto il cambio della password, contatta subito il nostro <a href=''>supporto</a> per proteggere il tuo account.</p>
                <p>Grazie,<br>Il team del Benedetto Radice</p>";
                $mail->send();
                unset($_SESSION["email"], $_SESSION["token"]);
                echo "ok";
        } catch (Exception $e) {
            echo "Errore invio email: {$mail->ErrorInfo}";
        }

        }
    }
?>
