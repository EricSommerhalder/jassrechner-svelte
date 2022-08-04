<?php
// We need to use sessions, so you should always start sessions using the below code.
session_start();
// If the user is not logged in redirect to the login page...
require('php/util.php');
if (!isset($_SESSION['id'])) {
	header('Location: login-page.php');
	exit;
}
if (!isset($_SESSION['activeGroup'])) {
    getActiveGroup($_SESSION['id']);
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
    <link rel="stylesheet" href="css/seatorder.css">
    <link rel="stylesheet" href="css/checkbox.css">
    <script type="text/javascript">
        function formatCheck() {
            if (document.getElementById('turnier').checked) {
                document.getElementById('turnierInfo').style.display = 'block';
            } else {
                document.getElementById('turnierInfo').style.display = 'none';
            }
            if (document.getElementById('geld').checked) {
                document.getElementById('geldInfo').style.display = 'block';
            } else {
                document.getElementById('geldInfo').style.display = 'none';
            }
        }

    </script>
</head>

<body>
    <nav>
        <ul>
            <li><a href="tafel-page.php">Tafel</a></li>
            <li>|</li>
            <li><a href="results-page.php">Resultate</a></li>
            <li>|</li>
            <li><a href="group-page.php">Gruppe</a></li>
            <li>|</li>
            <li><a href="user-page.php">Benutzer</a></li>
            <li>|</li>
            <li><a href="php/logout.php">Abmelden</a></li>
        </ul>
        <p class="crumb"><?=$_SESSION['name']?>, Du bisch igloggt</p>
        <p class="crumb">Aktivi Gruppe: <?php echo getGroupName($_SESSION['activeGroup'])?></p>
    </nav>
    <!--<div class="groupsection">
          <label class="selectGroup">Gruppe X
            <input type="radio" name="gruppenwahl">
            <span class="checkmark"></span>
          </label>
          <a class="deleteGroup">Diese Gruppe löschen</a>
        </div>-->
    <form action="php/createGroup.php" method="post">
        <input placeholder="Gruppename" name="groupname">
        <p>Wieviel jasse mit?</p>
        <div>
            <label class="selectionBtn" for="four">4 Spieler
                <input type="radio" id="four" name="noPlayers" value="4">
                <span class="checkmark"></span>
            </label>
        </div>
        <div>
            <label class="selectionBtn" for="six">6 Spieler
                <input type="radio" id="six" name="noPlayers" value="6">
                <span class="checkmark"></span>
            </label>
        </div>
        <div>
            <p>Weles Format jassedr?</p>
            <label class="selectionBtn" for="turnier">Turnier
                <input type="radio" onclick="javascript:formatCheck();" id="turnier" name="format" value="turnier">
                <span class="checkmark"></span>
            </label>
            <label class="selectionBtn" for="geld">Um Gäld
                <input type="radio" onclick="javascript:formatCheck();" id="geld" name="format" value="geld">
                <span class="checkmark"></span>
            </label>
        </div>
        <div id="turnierInfo" style="display:none">
            <input placeholder="Pünggt zum Turniersieg?" name="gewonnenBei" />
            <input placeholder="Pünggt füre Match?" name="punkteProMatch" />
            <input placeholder="Pünggt füre Kontermatch?" name="punkteProGegenmatch" />
            <input placeholder="Pünggt füre Sieg" name="punkteProSieg" />
        </div>
        <div id="geldInfo" style="display:none">
            <input placeholder="Gäld pro Punggt" name="geldProPunkt" />
            <input placeholder="Minimum pro Spieler" name="minimum" />
        </div>
        <p>Benutzer hinzuefiege, wo uf d Gruppe Zuegriff sölle ha.</p>
        <small>Mehreri durch ; trenne</small>
        <input placeholder="Benutzer" name="player" />
        <button type="submit">Gruppe erstelle</button>
    </form>

</body>

</html>
