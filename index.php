<?php
session_start();
$connessione = new mysqli("localhost","root","","Sql1868450_1");


$isConnect= isset($_SESSION["id"]);
if(isset($_SESSION["admin"])){
    $isAdmin = $_SESSION["admin"] === true;
}else{
  $isAdmin=false;
}


if($isConnect){
  $temp_data=date("m/d/Y");
  $temp_orario=date("H:i:s");
  $q="INSERT INTO accessi (data,orario) values ('$temp_data','$temp_orario')";
  $connessione->query($q);
}
$stringa_indice = "SELECT indice FROM _view WHERE link='radicechristmasharmony/'";
$indice = $connessione->query($stringa_indice);
if ($indice) {
    $connessione->query("UPDATE _view SET indice = indice + 1");
}

$giorni = [];
$days = date('j');
$mese = date('n');
$giorni_sbloccati = ($mese == 12) ? $days : 24;

for ($i = 1; $i <= 24; $i++) {

    $q = "SELECT classe, descrizione, link, link_lavoro, id_lavoro
          FROM rch_lavoro 
          WHERE giorno = '$i'";

    $risultato = $connessione->query($q);

    // default
    $giorni[$i] = [
        "id_lavoro"=> null,
        "classe"      => "",
        "descrizione" => "",
        "link_lavoro" => "",
        "bgStyle"     => "background-color: var(--green-light);"
    ];

    if ($risultato && $risultato->num_rows === 1) {
        $ris = $risultato->fetch_assoc();

        // blocco sblocco giorni
        $link_lavoro = ($i > $giorni_sbloccati) ? "" : $ris["link_lavoro"];
        $giorni[$i]["id_lavoro"] = $ris["id_lavoro"];
        $giorni[$i]["classe"]      = $ris["classe"];
        $giorni[$i]["descrizione"] = $ris["descrizione"];
        $giorni[$i]["link_lavoro"] = $link_lavoro;

        // background con UNA immagine
        if (!empty($ris["link"])) {
            $giorni[$i]["bgStyle"] =
                "background-image: url('{$ris["link"]}');" .
                "background-size: cover;" .
                "background-position: center;" .
                "background-repeat: no-repeat;";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Radice Christmas Harmony</title>
<link href='https://fonts.googleapis.com/css?family=Snowburst One' rel='stylesheet'>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="style.css">
<link rel="icon" type="image/png" href="logo.png">  
</head>
<body>
<div id="preloader">
  <img src="preloader.gif" alt="Caricamento..." class="preloader-gif">
</div>

<div class="menu-toggle" id="menuToggle">
  <span></span>
  <span></span>
</div>

<nav class="side-menu" id="sideMenu">
  <ul>
    <li><a href="elenco_p.php">Elenco Partecipanti</a></li>
    <li><a href="regolamento.php">Regolamento</a></li>
    <li>
      <a href="#" id="openLogin">
        <img 
          src="https://cdn-icons-png.flaticon.com/512/847/847969.png"
          alt="Accedi"
          style="width:30px; vertical-align:middle; margin-right:5px;"
        >
        <?php echo ($isConnect) ? "Connesso": "Accedi"?>
      </a>
    </li>
    <li>
      <?php
          if($isAdmin){
            echo "<a href='pannello/pannello.php'>Dashboard</a>";
          }
      ?>
    </li>
  </ul>
  <p class="text-right-note">Powered by Emanuele Ferrara</p>
</nav>

<div class="overlay" id="overlay"></div>
<div id="toast"></div>

<main class="site-bg">
  <div class="container main-container py-5">
    <header class="hero text-center mb-4">
      <h1 class="brand">Radice Christmas Harmony</h1>
    </header>

    <!-- GRID GIORNI -->
    <section id="daysGrid" class="grid-days" aria-label="Lista giorni">
      <?php for ($i = 1; $i <= 24; $i++): ?>
       <article class="day-item"
          data-id_lavoro="<?= $giorni[$i]['id_lavoro'] ?>"
          data-classe="<?= htmlspecialchars($giorni[$i]['classe']) ?>"
          data-descrizione="<?= htmlspecialchars($giorni[$i]['descrizione']) ?>"
          data-link="<?= htmlspecialchars($giorni[$i]['link_lavoro']) ?>">
          <div class="day-card" style="<?= $giorni[$i]['bgStyle'] ?>">
          <div class="day-title">Giorno <?= $i ?></div>
         </div>
        </article>
      <?php endfor; ?>
    </section>

    <!-- INFO PANEL -->
    <aside id="infoPanel" class="info-panel" aria-hidden="true">
      <div class="info-single-card" id_lavoro="">
        <h4 class="info-label">Classe</h4>
        <p id="info-classe-1">N/D</p>

        <h4 class="info-label mt-3">Descrizione</h4>
        <p id="info-descrizione-1">N/D</p>

        <button class="heart-btn" aria-label="Preferito">❤</button>
      </div>
    </aside>



    <button id="closeBtn" class="close-btn" aria-label="Chiudi pannello" title="Chiudi">⤺</button>
  </div>
</main>

<?php
if ($indice) {
    $dati = $indice->fetch_assoc();
    $numero = str_pad($dati['indice'], 6, "0", STR_PAD_LEFT);
    echo "<div class='text-left-note' id='hide'><p>$numero</p></div>";
}
$connessione->close();
?>

<!-- POPUP LOGIN -->
<div class="popup" id="popupLogin">
  <h3>Accedi</h3>
  <form id="formLogin">
    <input type="email" id="loginEmail" placeholder="Email" required>
    <input type="password" id="loginPassword" placeholder="Password" required>
    <div class="switch-popup" id="toRecovery" style="text-align: left; text-decoration:none;">Password Dimenticata?</div>
    <button type="submit" id="btnLogin">Accedi</button>
    <div class="switch-popup" id="toRegister">Registrati</div>
  </form>
</div>

<!-- POPUP REGISTRAZIONE -->
<div class="popup" id="popupRegister">
  <h3>Registrazione</h3>
  <form id="formRegister">
    <input type="email" id="regEmail" placeholder="Email @isradice.edu.it" required>
    <input type="password" id="regPass1"  placeholder="Password" required>
    <input type="password" id="regPass2" placeholder="Ripeti Password" required>
    <button type="submit" id="btnRegister">Continua</button>
    <div class="switch-popup" id="toLogin">Accedi</div>
  </form>
</div>

<!-- POPUP OTP -->
<div class="popup" id="popupOtp">
  <h3>Verifica OTP</h3>
  <form id="formOtp">
    <p style="color: #FFD700; text-align:left;">Ti abbiamo inviato un’email contenente il codice di conferma. Controlla la tua casella di posta e inserisci il codice per continuare.</p>
    <input type="text" id="otpCode" name="codeOtp" placeholder="Inserisci OTP" required>
    <button type="submit" id="btnOtp">Verifica</button>
  </form>
</div>

<!-- POPUP RECOVERY -->
<div class="popup" id="popupRecovery">
  <h3>Recupero Password</h3>
  <form id=formRecovery>
    <input type="email" id="emailRecovery" placeholder="Inserisci l'email" required>
    <button type="submit">Continua</button>
  </form>
</div>

<!-- POPUP INFO -->
<div class="popup" id="popupInfo">
    <h3>Ti è stata inviata un'email con le istruzioni per recuperare la password.</h3>
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
  function showToast(msg, type = 'info', duration = 3500) {
  const toast = document.getElementById('toast');
  toast.textContent = msg;
  toast.className = `show ${type}`;

  setTimeout(() => {
    toast.className = '';
  }, duration);
}

  const dayItems = Array.from(document.querySelectorAll('.day-item .day-card'));
  const infoPanel = document.getElementById('infoPanel');
  const closeBtn = document.getElementById('closeBtn');
  const view = document.getElementById('hide');
  const overlay = document.getElementById('overlay');
  const menuToggle = document.getElementById('menuToggle');
  const sideMenu = document.getElementById('sideMenu');

  // POPUP
  const popupLogin = document.getElementById('popupLogin');
  const popupRegister = document.getElementById('popupRegister');
  const popupOtp = document.getElementById('popupOtp');
  const openLoginBtn = document.getElementById('openLogin');
  const toRegister = document.getElementById('toRegister');
  const toLogin = document.getElementById('toLogin');
  const popupRecovery= document.getElementById("popupRecovery");
  const popupInfo=document.getElementById("popupInfo");
  const toRecovery=document.getElementById("toRecovery");

  function closeAllPopups() {
    popupLogin.classList.remove('active');
    popupRegister.classList.remove('active');
    popupOtp.classList.remove('active');
    popupInfo.classList.remove('active');
    popupRecovery.classList.remove('active');
    overlay.classList.remove('active');
    document.body.classList.remove('no-scroll');
  }

  function openPopup(popup) {
    closeAllPopups();
    popup.classList.add('active');
    overlay.classList.add('active');
    document.body.classList.add('no-scroll');
    // chiudi menu
    menuToggle.classList.remove('open');
    sideMenu.classList.remove('open');
  }

  openLoginBtn.addEventListener('click', e => { e.preventDefault(); const con = <?=$isConnect ? 'true' : 'false' ?>;
  if(con) return; openPopup(popupLogin); });
  toRegister.addEventListener('click', () => openPopup(popupRegister));
  toLogin.addEventListener('click', () => openPopup(popupLogin));
  toRecovery.addEventListener("click", () => openPopup(popupRecovery));
  overlay.addEventListener('click', closeAllPopups);

  // ---------------- GRID CARD ----------------
  function getCardFromTarget(t) { if (!t) return null; if (t.classList && t.classList.contains('day-card')) return t; return t.closest ? t.closest('.day-card') : null; }
  function fillInfoFromCard(card) {
  if (!card) return;

  const wrapper = card.parentElement;

  const classe = wrapper.dataset.classe || "N/D";
  const descr  = wrapper.dataset.descrizione || "N/D";
  const link   = wrapper.dataset.link || "";
  const idLavoro = wrapper.dataset.id_lavoro || "";

  const elClasse = document.getElementById('info-classe-1');
  const elDescr  = document.getElementById('info-descrizione-1');
  const heartBtn = infoPanel.querySelector('.heart-btn');

  if (heartBtn) {
    heartBtn.dataset.id_lavoro = idLavoro;
    heartBtn.classList.remove('active');
    syncLikeState(idLavoro, heartBtn);    
  }

  if (elClasse) {
    elClasse.textContent = classe;
  }

  if (elDescr) {
    elDescr.innerHTML = "";
    if (link) {
      const a = document.createElement('a');
      a.href = link;
      a.textContent = descr || link;
      a.target = "_blank";
      a.rel = "noopener noreferrer";
      a.classList.add("descr-box");
      elDescr.appendChild(a);
    } else {
      elDescr.textContent = descr;
    }
  }
}

async function syncLikeState(idLavoro, heartBtn) {
  if (!idLavoro || !heartBtn) return;

  const formData = new FormData();
  formData.append('id_lavoro', idLavoro);

  const res = await fetch('controlloLike.php', {
    method: 'POST',
    body: formData
  });

  const result = (await res.text()).trim();

  if (result === 'like') {
    heartBtn.classList.add('active');
  } else if(result==="unlike"){
    heartBtn.classList.remove('active');
  }
}



  function openCard(card) {
    if(!card) return;
    dayItems.forEach(c=>{ if(c!==card)c.classList.add('hidden'); else c.classList.remove('hidden'); });
    card.classList.add('active');
    infoPanel.classList.add('show'); infoPanel.setAttribute('aria-hidden','false');
    closeBtn.classList.add('show');
    if(view) view.style.display="none";
    document.body.scrollTop=0;
    fillInfoFromCard(card);
  }

  function closePanel() {
    dayItems.forEach(c=>{ c.classList.remove('hidden'); c.classList.remove('active'); });
    infoPanel.classList.remove('show'); infoPanel.setAttribute('aria-hidden','true');
    closeBtn.classList.remove('show');
    if(view) view.style.display="block";
  }

  document.querySelectorAll('.day-item').forEach(wrapper => {
    wrapper.addEventListener('click', ev => openCard(getCardFromTarget(ev.target)));
    wrapper.addEventListener('keydown', ev => { if(ev.key==='Enter'||ev.key===' '){ ev.preventDefault(); openCard(wrapper.querySelector('.day-card')); }}); 
  });
  closeBtn.addEventListener('click', closePanel);
  document.addEventListener('keydown', ev => { if(ev.key==='Escape') closePanel(); });
  document.addEventListener('click', ev => {
    const active = document.querySelector('.day-card.active');
    if(!active) return;
    const insideActive = active.contains(ev.target) || infoPanel.contains(ev.target) || closeBtn.contains(ev.target);
    if(!insideActive) closePanel();
  });

  // ---------------- MENU HAMBURGER ----------------
  menuToggle.addEventListener('click', () => {
    menuToggle.classList.toggle('open');
    sideMenu.classList.toggle('open');
    overlay.classList.toggle('active');
  });

  // ---------------- NEVE ----------------
  function createSnowflakes(count){ for(let i=0;i<count;i++){ const s=document.createElement('div'); s.classList.add('snowflake'); s.style.left=Math.random()*window.innerWidth+'px'; s.style.fontSize=(Math.random()*10+10)+'px'; s.style.animationDuration=(Math.random()*5+5)+'s'; s.textContent='❄'; document.body.appendChild(s);} }
  function resetSnowflakes(count){ document.querySelectorAll('.snowflake').forEach(el=>el.remove()); createSnowflakes(count);}
  createSnowflakes(100);
  if(window.innerWidth>=768){ window.addEventListener('resize', ()=>{ resetSnowflakes(100); }); }

  // ---------------- PRELOADER ----------------
  window.addEventListener("load", function() {
    const preloader = document.getElementById("preloader");
    const minDuration = 1000;
    const start = Date.now();
    const hidePreloader = () => { preloader.style.opacity=0; setTimeout(()=>preloader.style.display='none',500);}
    const elapsed = Date.now()-start;
    if(elapsed<minDuration){ setTimeout(hidePreloader,minDuration-elapsed); } else { hidePreloader(); }
  });

  // ---------------- AJAX REGISTRAZIONE ----------------
  formRegister.addEventListener('submit', async (e) => {
  e.preventDefault();
  const email = document.getElementById('regEmail').value.trim();
  const pass1 = document.getElementById('regPass1').value;
  const pass2 = document.getElementById('regPass2').value;

  if(pass1 !== pass2){
    showToast("Password non coicidono","info");
    return;
  }

  try {
    const formData = new FormData();
    formData.append('email', email);
    formData.append('password', pass1);

    const response = await fetch("register.php", {
      method: 'POST',
      body: formData
    });
    const result = await response.text();

    if(result.trim() === "ok"){
      popupRegister.classList.remove('active');  
      openPopup(popupOtp);                      
    } else {
      alert(result);
    }

  } catch(err){
    showToast("Si è verificato un problema, riprova più tardi","info");
  }
});


  // ---------------- AJAX LOGIN ----------------
const formLogin = document.getElementById('formLogin');
formLogin.addEventListener('submit', async (e) => {
  e.preventDefault();
  const email = document.getElementById('loginEmail').value.trim();
  const password = document.getElementById('loginPassword').value;
  try {
    const formData = new FormData();
    formData.append('email', email);
    formData.append('password', password);

    const response = await fetch('login.php', {
      method: 'POST',
      body: formData
    });
    const result = await response.text();
    if(result.trim() === "ok"){
      closeAllPopups();
      showToast("Login effettuato con successo!","success");
      location.reload();
    } else {
      showToast(result,"info");
    }
  } catch(err){
    showToast("Si è verificato un problema, riprova più tardi","info");
  }
});

// ---------------- AJAX OTP ----------------
const formOtp = document.getElementById('formOtp');
formOtp.addEventListener('submit', async (e) => {
  e.preventDefault();

  const otpCode = document.getElementById('otpCode').value.trim();

  try {
    const formData = new FormData();
    formData.append('codeOtp', otpCode); 

    const response = await fetch('register.php', {
      method: 'POST',
      body: formData
    });
    const result = await response.text();

    if(result.trim() === "ok"){
      closeAllPopups();
      showToast("Registrazione completata!","success");
      location.reload();
    } else {
      showToast(result,"info");
    }
  } catch(err){
    showToast("Si è verificato un problema, riprova più tardi","info");
  }
});

// AJAX POPUP RECOVERY

const formrecovery=document.getElementById("formRecovery");
formrecovery.addEventListener('submit', async(e)=>{
    e.preventDefault();
    const email=document.getElementById("emailRecovery").value.trim();

    const formdata= new FormData();
    formdata.append("email",email);
    try{
        const result= await fetch("recovery_password.php",{
        method: 'POST',
        body: formdata
      });
      const res= await result.text();
      if(res==="ok"){
          closeAllPopups();
          openPopup(popupInfo);
      }else{
          showToast(res,"info");
      }
    }catch(err){
    }
    
});


document.addEventListener('click', (ev) => {
  const isClickInsideMenu = sideMenu.contains(ev.target) || menuToggle.contains(ev.target);
  const isMenuOpen = sideMenu.classList.contains('open');

  if(isMenuOpen && !isClickInsideMenu){
    // chiudi menu e overlay
    sideMenu.classList.remove('open');
    menuToggle.classList.remove('open');
    overlay.classList.remove('active');
  }
});
  const heartBtn = document.querySelector('.heart-btn');
  const collegato = <?=$isConnect ? 'true' : 'false' ?>;

  // gestione click sul cuore
  heartBtn.addEventListener('click', async () => {
    const idLavoro = heartBtn.dataset.id_lavoro;
    if(!collegato || !idLavoro) {
      closePanel();
      openPopup(popupLogin);
      return;
    }
    heartBtn.classList.toggle('active');
    const isLike = heartBtn.classList.contains('active');
    try {
      const formData = new FormData();
      formData.append('id_lavoro', idLavoro);
      formData.append('action', isLike ? 'like' : 'unlike'); 

      const response = await fetch('like.php', {

        method: 'POST',
        body: formData
      });

      const resultTrim =  await response.text();

      if(resultTrim === "ok"){
      } else if(resultTrim === "limite"){
        showToast("Hai già messo 3 like, non puoi aggiungerne altri","info");
        heartBtn.classList.toggle('active');
      } else if(resultTrim==="errore"){
        showToast("Impossibile completare l’azione","info");
        heartBtn.classList.toggle('active'); 
      }

    } catch (err) {
      showToast("Si è verificato un problema, riprova più tardi","info");
      heartBtn.classList.toggle('active'); 
    }
  });
});
</script>
</body>
</html>