<?php
    session_start();
    $connessione= new mysqli("localhost","root","","Sql1868450_1");

    if(!isset($_SESSION["admin"]) || $_SESSION["admin"]!=true) header("Location: https://www.iragazzidelradice.it/radicechristmasharmony/");
    
    $queryGetView="SELECT indice FROM _view where link='radicechristmasharmony/'";
    $queryGetTotVoti="SELECT count(id_lavoro) as voti FROM like_lavori";
    $queryGetVotiValidi="SELECT count(*) as numero_utenti From (SELECT id_utente FROM like_lavori GROUP BY id_utente HAVING COUNT(*)= 3) as t";
    $queryGetVotiNonValidi="SELECT COUNT(*) AS numero_voti FROM like_lavori WHERE id_utente IN (SELECT id_utente FROM like_lavori GROUP BY id_utente HAVING COUNT(*) <= 2);";
    $queryGetTotRegistrati="SELECT COUNT(*) AS utenti from utenti";
    $queryGetListaRegistratiNonValidi="SELECT email from utenti join like_lavori on utenti.id_utente=like_lavori.id_utente where utenti.id_utente in(select id_utente from like_lavori group by id_utente having count(*) <=2) group by email";
    $queryGetDateLavori="SELECT r.classe, COUNT(l.id_utente) AS numero_voti FROM rch_lavoro r JOIN like_lavori l ON l.id_lavoro = r.id_lavoro WHERE l.id_utente IN (SELECT id_utente FROM like_lavori GROUP BY id_utente HAVING COUNT(*) = 3) GROUP BY r.classe;";

    // contatatore visual
    $temp_view=$connessione->query($queryGetView);
    $view=$temp_view->fetch_assoc();
    $view=$view["indice"];
    
    // voti Totali
    $temp_tot_voti=$connessione->query($queryGetTotVoti);
    if($temp_tot_voti->num_rows>0){
        $TotVoti=$temp_tot_voti->fetch_assoc();
        $TotVoti=$TotVoti["voti"];
    }

    // voti validi
    $temp_voti_validi=$connessione->query($queryGetVotiValidi);
    if($temp_voti_validi->num_rows>0){
        $votiValidi=$temp_voti_validi->fetch_assoc();
        $votiValidi=$votiValidi["numero_utenti"]*3;
    }
    
    //voti non validi
    $temp_voti_non_validi=$connessione->query($queryGetVotiNonValidi);
    if($temp_voti_non_validi->num_rows>0){
        $votiNonValidi=$temp_voti_non_validi->fetch_assoc();
        $votiNonValidi=$votiNonValidi["numero_voti"];
    }

    //Account Registrati
    $temp_A_registrati=$connessione->query($queryGetTotRegistrati);
    if($temp_A_registrati->num_rows>0){
        $accountRegistrati=$temp_A_registrati->fetch_assoc();
        $accountRegistrati=$accountRegistrati["utenti"];
    }


    //Valori per istogramma
    $var=$connessione->query($queryGetDateLavori);
    if($var->num_rows>0){
        $data = [];
        while($r = $var->fetch_assoc()){
            $data[] = [
                'classe' => $r['classe'],
                'numero_voti' => $r['numero_voti']
            ];
        }
        $valori = json_encode($data);
    }

    //email con voti non valide
    $tempEmail=$connessione->query($queryGetListaRegistratiNonValidi);
    $emailNonValide=[];
    if($tempEmail->num_rows>0){
        while ($r=$tempEmail->fetch_assoc()){
            $emailNonValide[]= $r['email'];
        }
        $jsonEmail=json_encode($emailNonValide);
    }

?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" href="logo2.png">
</head>
<body>
<div id="toast"></div>
<div class="dashboard">

    <!-- AREA PRINCIPALE -->
    <main class="main">

        <!-- BLOCCO SUPERIORE -->
        <section class="top">

            <!-- Tabella 3x2 -->
            <table class="card">
                <tr><td>TOT Voti</td><td><?php echo $TotVoti?></td></tr>
                <tr><td>Voti Validi</td><td><?php echo $votiValidi?></td></tr>
                <tr><td>Voti Non Validi</td><td><?php echo $votiNonValidi?></td></tr>
            </table>

            <!-- Tabella 1x2 -->
            <table class="card">
                <tr>
                    <td>Account Registrati</td>
                    <td style="text-align: center;"><?php echo $accountRegistrati?></td>
                </tr>
            </table>

            <!-- Tabella 1x2 -->
            <table class="card">
                <tr><td>Visualizzazioni</td><td><?php echo $view?></td></tr>
            </table>

        </section>

        <!-- ISTOGRAMMA (placeholder) -->
        <section class="chart">
            <canvas id="gradesChart"></canvas>
        </section>

        <Section class="right">
            <div class="table-container">
                <table class="card">
                    <thead>
                        <tr><th>Utenti con voti incompleti</th></tr>
                    </thead>
                    <tbody>
                        <?php
                        if(count($emailNonValide)>0){
                            for($i=0;$i<count($emailNonValide);$i++){
                                echo "<tr>";
                                echo "<td>".$emailNonValide[$i]."</td>";
                                echo "</tr>";
                            }
                        }else{
                            echo "<tr>";
                            echo "<td>Nessun utente con voti incompleti.</td>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </Section>
        
        <section class="top-right">
            <button id="button">Avvisa utenti voti mancanti</button>
        </section>

    </main>
</div>

</body>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  function showToast(msg, type = 'info', duration = 3500) {
  const toast = document.getElementById('toast');
  toast.textContent = msg;
  toast.className = `show ${type}`;

  setTimeout(() => {
    toast.className = '';
  }, duration);
}
const valori = <?php echo $valori; ?>;

const labels = [];
const votes = [];

for(let i = 0; i < valori.length; i++){
    labels.push(valori[i].classe);
    votes.push(valori[i].numero_voti);
}

const ctx = document.getElementById('gradesChart').getContext('2d');

new Chart(ctx, {
    type: 'bar',
    data: {
        labels: labels, 
        datasets: [{
            label: 'Voti',
            data: votes,
            backgroundColor: 'rgba(0, 31, 63, 0.8)',
            borderColor: 'rgba(0, 31, 63, 1)',  
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true,  
                min: 0,             
                ticks: {
                    stepSize: 5,    
                    font: { size: 16 }
                },
                grid: {
                    drawTicks: true,
                    drawBorder: true,
                    color: '#ddd' 
                }
            },
            x: {
                ticks: {
                    font: { size: 14 }
                },
                grid: {
                    drawTicks: false,
                    drawBorder: false,
                    display: false
                }
            }
        },
        plugins: {
            legend: { display: false }
        }
    }
});
const email= <?php echo $jsonEmail;?>;
const button =document.getElementById('button');
button.addEventListener("click", async function(){

    const formdata= new FormData();
    formdata.append("promemoria","1");
    formdata.append("emailNonValide",JSON.stringify(email));
    try{
        const result= await fetch("../register.php",{
            method: 'POST',
            body: formdata,
        });

        const res= await result.text();
        if(res==="ok"){
            showToast("Promemoria Inviato");
        }else{
            showToast(res);
        }
    }catch(err){
        showToast(err);
    }
});

</script>


</html>
