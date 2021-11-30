<?php
// We need to use sessions, so you should always start sessions using the below code.
session_start();
require('php/util.php');
// If the user is not logged in redirect to the login page...
if (!isset($_SESSION['id'])) {
	header('Location: login-page.php');
	exit;
}

$group = getActiveGroup($_SESSION['id']);
if ($group == NULL){
  header('Location: group-page.php');
  exit;
} 

  $game = getActiveGame($_SESSION['activeGroup']);
  if ($game == NULL){
    header('Location: group-page.php');
    exit;
  }
  getAusgeber();
  if (!isset($_SESSION['ausgeber']) || $_SESSION['ausgeber'] == NULL){
    header('Location: group-page.php');
    exit;
  }
  getNoPlayers();
  if (!isset($_SESSION['noPlayers']) || $_SESSION['noPlayers'] == NULL){
    header('Location: group-page.php');
    exit;
  }
  getPlayerNames();
  if (!isset($_SESSION['players']) || $_SESSION['players'] == NULL){
    header('Location: group-page.php');
    exit;
  }
  getTeamNames();
  if (!isset($_SESSION['teamnames']) || $_SESSION['teamnames'] == NULL){
    header('Location: group-page.php');
    exit;
  }
  getTafel();
  if (!isset($_SESSION['tafel']) || $_SESSION['tafel'] == NULL){
    header('Location: group-page.php');
    exit;
  }

?>
<!DOCTYPE html>
<script>
  function focusOut(id){
    var x = document.getElementById(id);
    document.getElementById(id.charAt(0) + '0').style.backgroundColor="";
    document.getElementById('Diszi' + id.substring(1)).style.backgroundColor="";
    let val = x.value.trim();
    if (val == ''){
      return;
    }
    fetch('php/setTafelValue.php', {
      method: 'POST',
      headers: {
          "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8",
        },
      body: `id=${id}&value=${val}`,
    })
    x.disabled = true;
  }
  function highlight(id){
    document.getElementById(id.charAt(0) + '0').style.backgroundColor="green";
    document.getElementById('Diszi' + id.substring(1)).style.backgroundColor="green";
  }
</script>
<html lang="de">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jassrechner</title>
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/styles.css ">
  </head>
  <body>
    <nav>
        <ul>
            <li><a>Abmelden</a></li>
            <li>|</li>
            <li><a>Tafel</a></li>
            <li>|</li>
            <li><a>Resultate</a></li>
            <li>|</li>
            <li><a>Einstellungen</a></li>
        </ul>
        <?php echo "BenutzerId: " . $_SESSION['id'] . " Aktive Gruppe: " . $_SESSION['activeGroup'] . " Aktives Spiel " . $_SESSION['activeGame'] ?>
    </nav>
    <div class="content">
      <aside class="leftside"></aside>
      <main>
        <div class="table">
            <table>
                <tr><td></td><td id="A0"><?php echo $_SESSION['teamnames'][0]?></td><td id="B0"><?php echo $_SESSION['teamnames'][1]?></td></tr>
                <tr><td id="Diszi1">Schuufle</td><td><input id="A1" value="<?php if ($_SESSION['tafel'][0] == -1) {echo '';} else { echo $_SESSION['tafel'][0];} ?>" onfocusout="focusOut('A1')" onfocus="highlight('A1')"/></td><td><input id="B1" value="<?php if ($_SESSION['tafel'][10] == -1) {echo '';} else { echo $_SESSION['tafel'][10];} ?>" onfocusout="focusOut('B1')" onfocus="highlight('B1')"/></td></tr>
                <tr><td id="Diszi2">Chrüüz</td><td><input id="A2" value="<?php if ($_SESSION['tafel'][1] == -1) {echo '';} else { echo $_SESSION['tafel'][1];} ?>" onfocusout="focusOut('A2')" onfocus="highlight('A2')"/></td><td><input id="B2" value="<?php if ($_SESSION['tafel'][11] == -1) {echo '';} else { echo $_SESSION['tafel'][11];} ?>" onfocusout="focusOut('B2')" onfocus="highlight('B2')"/></td></tr>
                <tr><td id="Diszi3">Egge</td><td><input id="A3" value="<?php if ($_SESSION['tafel'][2] == -1) {echo '';} else { echo $_SESSION['tafel'][2];} ?>" onfocusout="focusOut('A3')" onfocus="highlight('A3')"/></td><td><input id="B3" value="<?php if ($_SESSION['tafel'][12] == -1) {echo '';} else { echo $_SESSION['tafel'][12];} ?>" onfocusout="focusOut('B3')" onfocus="highlight('B3')"/></td></tr>
                <tr><td id="Diszi4">Härz</td><td><input id="A4" value="<?php if ($_SESSION['tafel'][3] == -1) {echo '';} else { echo $_SESSION['tafel'][3];} ?>" onfocusout="focusOut('A4')" onfocus="highlight('A4')"/></td><td><input id="B4" value="<?php if ($_SESSION['tafel'][13] == -1) {echo '';} else { echo $_SESSION['tafel'][13];} ?>" onfocusout="focusOut('B4')" onfocus="highlight('B4')"/></td></tr>
                <tr><td id="Diszi5">Misere</td><td><input id="A5" value="<?php if ($_SESSION['tafel'][4] == -1) {echo '';} else { echo $_SESSION['tafel'][4];} ?>" onfocusout="focusOut('A5')" onfocus="highlight('A5')"/></td><td><input id="B5" value="<?php if ($_SESSION['tafel'][14] == -1) {echo '';} else { echo $_SESSION['tafel'][14];} ?>" onfocusout="focusOut('B5')" onfocus="highlight('B5')"/></td></tr>
                <tr><td id="Diszi6">Unde</td><td><input id="A6" value="<?php if ($_SESSION['tafel'][5] == -1) {echo '';} else { echo $_SESSION['tafel'][5];} ?>" onfocusout="focusOut('A6')" onfocus="highlight('A6')"/></td><td><input id="B6" value="<?php if ($_SESSION['tafel'][15] == -1) {echo '';} else { echo $_SESSION['tafel'][15];} ?>" onfocusout="focusOut('B6')" onfocus="highlight('B6')"/></td></tr>
                <tr><td id="Diszi7">Obe</td><td><input id="A7" value="<?php if ($_SESSION['tafel'][6] == -1) {echo '';} else { echo $_SESSION['tafel'][6];} ?>" onfocusout="focusOut('A7')" onfocus="highlight('A7')"/></td><td><input id="B7" value="<?php if ($_SESSION['tafel'][16] == -1) {echo '';} else { echo $_SESSION['tafel'][16];} ?>" onfocusout="focusOut('B7')" onfocus="highlight('B7')"/></td></tr>
                <tr><td id="Diszi8">Slalom</td><td><input id="A8" value="<?php if ($_SESSION['tafel'][7] == -1) {echo '';} else { echo $_SESSION['tafel'][7];} ?>" onfocusout="focusOut('A8')" onfocus="highlight('A8')"/></td><td><input id="B8" value="<?php if ($_SESSION['tafel'][17] == -1) {echo '';} else { echo $_SESSION['tafel'][17];} ?>" onfocusout="focusOut('B8')" onfocus="highlight('B8')"/></td></tr>
                <tr><td id="Diszi9"><?php if ($_SESSION['noPlayers'] == 6){ echo '2-2-2' ;} elseif ($_SESSION['noPlayers'] == 4){ echo '3-3-3' ;} else {echo 'kei Ahnig';}?></td><td><input id="A9" value="<?php if ($_SESSION['tafel'][8] == -1) {echo '';} else { echo $_SESSION['tafel'][8];} ?>" onfocusout="focusOut('A9')" onfocus="highlight('A9')"/></td><td><input id="B9" value="<?php if ($_SESSION['tafel'][18] == -1) {echo '';} else { echo $_SESSION['tafel'][18];} ?>" onfocusout="focusOut('B9')" onfocus="highlight('B9')"/></td></tr>
                <tr><td id="Diszi10">Frei</td><td><input id="A10" value="<?php if ($_SESSION['tafel'][9] == -1) {echo '';} else { echo $_SESSION['tafel'][9];} ?>" onfocusout="focusOut('A10')" onfocus="highlight('A10')"/></td><td><input id="B10" value="<?php if ($_SESSION['tafel'][19] == -1) {echo '';} else { echo $_SESSION['tafel'][19];} ?>" onfocusout="focusOut('B10')" onfocus="highlight('B10')"/></td></tr>
                <tr><td>Total</td><td></td><td></td></tr>
                <tr><td>Vorsprung</td><td></td><td></td></tr>
            </table>
        </div>
      </main>
      <aside class="rightside">
        <p>Uusgäh dörf: <?php echo $_SESSION['players'][$_SESSION['ausgeber']] ?></p>
        <div class="infobox_turnier">
          <table>

          </table>
        </div>
        <div class="infobox_cashgame">
          <table>
            
          </table>
        </div>
        <p> <?php foreach($_SESSION['tafel'] as &$a) { echo $a;} ?> </p>
      </aside>
    </div>
    <footer>
    </footer>

  </body>
  <script>
    for (const c of ['A', 'B']){
      for (let i=1; i < 11; i++){
        const id = c + i.toString();
        var input = document.getElementById(id);
        if (input.value != -1 && input.value != ''){
          input.disabled=true;
        } 
        input.addEventListener("keyup", function(event) {
        if (event.keyCode === 13) {
            event.preventDefault();
            focusOut(id);
  }});
      }
    }
    
  </script>
</html>