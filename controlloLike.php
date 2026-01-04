<?php
    session_start();
    $connessione= new mysqli("localhost","root","","Sql1868450_1");

    if(isset($_SESSION["id"])){
        $id_utente=$_SESSION["id"];
        $id_lavoro=$_POST["id_lavoro"];
        $query="SELECT * FROM like_lavori where id_utente=$id_utente and id_lavoro=$id_lavoro";
        $res=$connessione->query($query);
        if($res->num_rows>0){
            echo "like";
        }else{
            echo "unlike";
        }
    }else{
        echo "errore";
    }
?>