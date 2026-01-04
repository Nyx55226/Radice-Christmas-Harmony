<?php
session_start();
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require 'PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$conn = new mysqli("localhost","root","","Sql1868450_1");

if(isset($_POST['email']) && isset($_POST['password'])){
    $email = $_POST['email'];
    $password = $_POST['password'];

    if(!str_ends_with($email, "@isradice.edu.it")){
        echo "Email non valida";
        exit;
    }

    if(!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/', $password)){
        echo "Password non valida";
        exit;
    }

    $queryControlloEmail="SELECT id_utente FROM utenti WHERE email='$email'";
    $res=$conn->query($queryControlloEmail);
    if($res->num_rows>0){
        echo "Email già registrata";
        exit;
    }

    $otp = rand(100000, 999999);
    $_SESSION['code'] = $otp;
    $_SESSION['email'] = $email;
    $_SESSION['password'] = password_hash($password, PASSWORD_DEFAULT);

    try {
        $mail = new PHPMailer(true);
        $mail->isMail();

        $mail->CharSet = 'UTF-8';
        $mail->setFrom(
        'radicechristmasharmony@iragazzidelradice.it',
        'Radice Christmas Harmony'
        );
        $mail->addAddress($email);
        $mail->Subject = 'Il tuo codice di conferma';
        $mail->Body    = "Ciao!\nIl tuo codice di conferma è: $otp";

        $mail->send();
        echo "ok";
    } catch (Exception $e) {
        echo "Errore invio email: {$mail->ErrorInfo}";
    }
    exit;
}

if(isset($_POST['codeOtp'])){
    
    if(!isset($_SESSION['code'], $_SESSION['email'], $_SESSION['password'])){
        echo "Nessun OTP generato";
        exit;
    }
    if($_POST["codeOtp"] != $_SESSION['code']){
        echo "OTP non valido";
        exit;
    }

    $queryAddUser = "INSERT INTO utenti (email, password) VALUES ('".$_SESSION['email']."','".$_SESSION['password']."')";

    if($conn->query($queryAddUser)){
        $queryGetIdUser="SELECT id_utente FROM utenti WHERE email='".$_SESSION['email']."'";
        $r=$conn->query($queryGetIdUser);
        if($r->num_rows>0){
            $t=$r->fetch_assoc();
            $_SESSION['id'] = $t["id_utente"];
        }
    }
    unset($_SESSION['otp'], $_SESSION['email'], $_SESSION['password']);

    echo "ok";
    exit;
}
if(isset($_POST["promemoria"])){
    $emailNonValide=[];
    if(isset($_POST["emailNonValide"])){
        $emailNonValide=json_decode($_POST["emailNonValide"],true);
        $success=true;
        $mail = new PHPMailer(true);
                $mail->isMail();
                $mail->CharSet = 'UTF-8';
                $mail->setFrom(
                'radicechristmasharmony@iragazzidelradice.it',
                'Radice Christmas Harmony');
        for($i=0;$i<count($emailNonValide);$i++){
            try {
                $mail->clearAddresses();
                $mail->addAddress($emailNonValide[$i]);
                $mail->Subject = 'I tuoi voti non sono ancora validi';
                $mail->isHTML(false); // esplicito
                $mail->Body =
                "Gentile studente,\n\n".
                "abbiamo rilevato che hai espresso solo 1 o 2 voti nella nostra piattaforma.\n".
                "Poiché per la validità dei voti è necessario esprimere tutti e 3 i voti previsti dal regolamento, ".
                "ti invitiamo a completare i voti rimanenti.\n\n".
                "Solo completando i tre voti i tuoi contributi saranno conteggiati correttamente.\n\n".
                "Ti ringraziamo per la collaborazione.\n".
                "Cordiali saluti,\n".
                "Il Team del Benedetto Radice";
                $mail->send();
            }catch (Exception $e) {
                $success=false;
            }
        }
        echo $success ? "ok": "Qualcosa è andato storto";
    }
}
?>
