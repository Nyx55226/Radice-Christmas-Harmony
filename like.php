<?php
session_start();
$connessione = new mysqli("localhost","root","","Sql1868450_1");


if(isset($_SESSION["id"])){
    $id_lavoro = $_POST["id_lavoro"];
    $azione = $_POST["action"];

    switch($azione){
        case "like":
            $queryC = "SELECT COUNT(*) as like_messi FROM like_lavori WHERE id_utente=".$_SESSION["id"];
            $res = $connessione->query($queryC);

            if($res){
                $r = $res->fetch_assoc();
                if($r["like_messi"] >= 3){
                    echo "limite";
                    exit;
                } else {
                    $queryAddLike = "INSERT INTO like_lavori(id_lavoro,id_utente) VALUES ($id_lavoro,".$_SESSION["id"].")";
                    if($connessione->query($queryAddLike)){
                        echo "ok";
                    } else {
                        echo "errore";
                    }
                }
            } else {
                echo "errore";
            }
            break;

        case "unlike":
            $queryDeleteLike = "DELETE FROM like_lavori WHERE id_utente=".$_SESSION["id"]." AND id_lavoro=$id_lavoro";
            if($connessione->query($queryDeleteLike)){
                echo "ok";
            } else {
                echo "errore";
            }
            break;
    }
}else{
    echo "errore";
}
?>
