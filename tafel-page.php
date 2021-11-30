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
    header('Location: group-page4.php');
    exit;
  }

?>
<!DOCTYPE html>
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
                <tr><td></td><td><?php echo $_SESSION['teamnames'][0]?></td><td><?php echo $_SESSION['teamnames'][1]?></td></tr>
                <tr><td>Schuufle</td><td></td><td></td></tr>
                <tr><td>Chrüüz</td><td></td><td></td></tr>
                <tr><td>Egge</td><td></td><td></td></tr>
                <tr><td>Härz</td><td></td><td></td></tr>
                <tr><td>Misere</td><td></td><td></td></tr>
                <tr><td>Unde</td><td></td><td></td></tr>
                <tr><td>Obe</td><td></td><td></td></tr>
                <tr><td>Slalom</td><td></td><td></td></tr>
                <tr><td><?php if ($_SESSION['noPlayers'] == 6){ echo '2-2-2' ;} elseif ($_SESSION['noPlayers'] == 4){ echo '3-3-3' ;} else {echo 'kei Ahnig';}?></td><td></td><td></td></tr>
                <tr><td>Frei</td><td></td><td></td></tr>
                <tr><td>Total</td><td></td><td></td></tr>
                <tr><td>Vorsprung</td><td></td><td></td></tr>
            </table>
        </div>
      </main>
      <aside class="rightside">
        <p>Uusgäh dörf: <?php echo $_SESSION['players'][$_SESSION['ausgeber']] ?></p>
      </aside>
    </div>
    <footer>
    </footer>

  </body>
</html>