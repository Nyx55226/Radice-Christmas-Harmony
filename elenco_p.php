<?php
$connessione= new mysqli("localhost","root","","Sql1868450_1");
  $stringa_join = "SELECT rch_lavoro.classe, rch_lavoro.shortdesc, rch_tipo_lavoro.descrizione 
              FROM rch_lavoro 
              INNER JOIN rch_tipo_lavoro 
              ON rch_lavoro.id_tipologia = rch_tipo_lavoro.id_tipologia
              ORDER BY rch_lavoro.classe DESC";

  $dati = $connessione->query($stringa_join);

  $stringa_indice="SELECT indice FROM _view WHERE link='radicechristmasharmony/'";
  $indice=$connessione->query($stringa_indice);
  if($indice){
    $stringa="UPDATE _view SET indice= indice+1";
    $connessione->query($stringa);
  }
?>
<!DOCTYPE html>
<html lang="it">
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
    <div class="menu-toggle" id="menuToggle">
      <span></span>
      <span></span>
    </div>
    <nav class="side-menu" id="sideMenu">
      <ul>
        <li><a href="index.php">Home</a></li>
        <li><a href="regolamento.php">Regolamento</a></li>
      </ul>
      <p class="text-right-note">Powered by Emanuele Ferrara</p>
    </nav>
    <div  class="overlay" id="overlay"></div>
    <header class="hero">
        <h1 class="brand">Radice Christmas Harmony</h1>
        <div class="hero-banner">
          <img src="banner.png" alt="Babbo Natale e pupazzo di neve" class="banner-img" />
          <p class="banner-text">In attesa dell'avvio ecco l'elenco dei lavori presi in carico (in aggiornamento).</p>
        </div>
    </header>
    <main class="container">
      <section class="grid-vertical">
          <?php
              if ($dati && $dati->num_rows > 0) {
                  while ($i = $dati->fetch_assoc()) {
                      echo "<div class='cell-vertical'>";
                      echo "<div class='cell-class'>" . htmlspecialchars($i['classe']) . "</div>";
                      echo "<div class='cell-shortdesc'>".htmlspecialchars($i['shortdesc'])."</div>";
                      echo "<div class='cell-type'>" . htmlspecialchars($i['descrizione']) . "</div>";
                      echo "</div>";
                  }
              }else{
                  echo "<p classe='banner-text'>Nessun lavoro preso in carico</p>";
              }
          ?>     
      </section>
    </main>
    <footer class="footer-note">
      <p>Se il tuo lavoro non figura nell'elenco fai un click 
        <a href="mailto:prof.michele.greco@isradice.edu.it">qui</a>.
      </p>
    </footer>
    <?php
      $indice= $indice->fetch_assoc();
      $indice= str_pad($indice['indice'],6,"0",STR_PAD_LEFT);
      echo "<div class='text-left-note'>";
      echo "<p>".$indice."</p>";
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
  </body>
</html>