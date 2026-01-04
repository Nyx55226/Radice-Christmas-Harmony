<?php
session_start();
$conn = new mysqli("localhost","root","","Sql1868450_1");

if(isset($_POST['email']) && isset($_POST['password'])){
    $email=$_POST['email'];
    $password= $_POST['password'];
    $queryGetIdUser="SELECT id_utente, is_admin,password FROM utenti WHERE email='$email'";
    $res=$conn->query($queryGetIdUser);
    
    if($res->num_rows === 0){
        echo "Email o password non corretti";
        exit;
    }

    $r = $res->fetch_assoc();
    if(!password_verify($password, $r['password'])){
        echo "Email o password non corretti";
        exit;
    }

    if($r["is_admin"]==1){
        $_SESSION["admin"]=true;
    }

    $_SESSION['id'] = $r['id_utente'];
    echo "ok";
    exit;
    }

echo "Dati mancanti";
?>
