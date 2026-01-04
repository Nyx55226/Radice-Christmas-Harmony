<?php
    $connessione= new mysqli("localhost","root","","Sql1868450_1");
    $query="SELECT indice FROM _view WHERE link='radicechristmasharmony/'";
    $indice=$connessione->query($query);
    if($indice){
        $query="UPDATE _view SET indice=indice+1";
        $connessione->query($query);
    }
?>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <title>Radice Christmas Harmony</title>
        <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700&family=Nunito:wght@400;600&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="style.css" />
        <link href='https://fonts.googleapis.com/css?family=Snowburst One' rel='stylesheet'>
        <link rel="icon" type="image/png" href="logo.png">  
    </head>
    <body class="theme-christmas">
  <!-- Menu a scomparsa -->
    <div class="menu-toggle" id="menuToggle"> 
        <span></span> 
        <span></span> 
    </div> 
    <nav class="side-menu" id="sideMenu"> 
        <ul> 
            <li><a href="index.php">Home</a></li> 
            <li><a href="elenco_p.php">Elenco Partecipanti</a></li> 
        </ul> 
        <p class="text-right-note">Powered by Emanuele Ferrara</p> 
    </nav> 
    <div class="overlay" id="overlay"></div>

  <!-- Hero -->
  <header class="regolamento-header text-center mb-4">
  <h1 class="regolamento-title">Radice Christmas Harmony</h1>
  <p class="regolamento-subtitle">Regolamento ufficiale del concorso 🎄</p>
</header>

  <main class="howToUsed container">
    <section class="intro">
      <p>Radice Christmas Harmony è la prima edizione del concorso a tema natalizio interamente ideato, organizzato e gestito dagli studenti dell'Istituto "Benedetto Radice".</p>
      <p>Benvenuti! Basta noia: il nostro calendario dell’avvento digitale svelerà ogni giorno una nuova creazione. Mettetevi comodi, perché lo spirito natalizio sta per esplodere!</p>
    </section>

    <section class="rules">
      <h2>🎁 Le regole principali</h2>
      <ul>
        <li>
          <strong>24 Caselle Magiche:</strong> Ogni giorno un nuovo capolavoro verrà svelato! Le vostre creazioni saranno pubblicate, una dopo l'altra.
        </li>
        <li>
          <strong>Accesso Super-Segreto:</strong> Per vedere tutti i contenuti, dovrete effettuare il <strong>LOGIN OBBLIGATORIO</strong> con il vostro account istituzionale <code>@ISRadice</code>.
        </li>
        <li>
          <strong>Quando si Vota:</strong> Dal 21 dicembre al 6 gennaio! Ogni utente potrà esprimere tre preferenze e solo voti completi saranno validi.
        </li>
      </ul>
      <p>Domande o dubbi? <a href="mailto:prof.michele.greco@isradice.edu.it">Scrivete qui</a></p>
    </section>
  </main>
</body>

    <?php
      $indice= $indice->fetch_assoc();
      $indice= str_pad($indice['indice'],6,"0",STR_PAD_LEFT);
      echo "<div class='text-left-note'>";
      echo "<p style='position: fixed; bottom: 0; left: 0; margin: 10px;'>".$indice."</p>";
      echo "</div>";
    ?>
    <script>
        function createSnowflakes(count) {
          for (let i = 0; i < count; i++) {
              const snowflake = document.createElement('div');
              snowflake.classList.add('snowflake');
              snowflake.style.left = Math.random() * window.innerWidth + 'px';
              snowflake.style.fontSize = (Math.random() * 10 + 10) + 'px';
              snowflake.style.animationDuration = (Math.random() * 5 + 5) + 's';
              snowflake.textContent = '❄';
              document.body.appendChild(snowflake);
          }
        }
      function resetSnowflakes(count) {
          document.querySelectorAll('.snowflake').forEach(el => el.remove());
          createSnowflakes(count);
      }
      createSnowflakes(100);
      if (window.innerWidth >= 768) {
          // Desktop: rigenera neve su resize
          window.addEventListener('resize', () => {
              resetSnowflakes(100);
          });
      }
        const menuToggle = document.getElementById('menuToggle');
        const sideMenu = document.getElementById('sideMenu');
        menuToggle.addEventListener('click', () => {
            menuToggle.classList.toggle('open');
            sideMenu.classList.toggle('open');
            overlay.classList.toggle('active');
        });
    </script>
</html>