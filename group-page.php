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
    <title>Jassrechner | Group</title>
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/styles.css ">
    <link rel="stylesheet" href="css/seatorder.css">
    <link rel="stylesheet" href="css/checkbox.css">
</head>
<script>
    function loadGroupSection(){
        
    }
</script> 
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
    <div class="content">
        <aside class="leftside">
            <?php 
            $arr = getGroups();
            for ($i = 0; $i < count($arr); $i++){
                $group = getGroupName($arr[$i]);
                echo "<div class=\"groupsection\">
                <label class=\"selectionBtn\">$group
                    <input type=\"radio\" name=\"gruppenwahl\">
                    <span class=\"checkmark\"></span>
                </label>
                <a class=\"deleteGroup\">Die Gruppe lösche</a>
            </div>";
            }
            ?>
            <button><a href="new-group-page.php">Neui Gruppe erstelle</a></button>
        </aside>
        <main>
            <h2>Di aktive Gruppe:</h2>
            <h3>Teamnamen:</h3>
            <div class="teamname">
                <input class="teamname" placeholder="Team A">
                <input class="teamname" placeholder="Team B">
            </div>
            <button type="reset">Teamname ändere</button>
            <button type="submit">Neui Teamname bestätige</button>
            <h3>Sitzordnung:</h3>
            <div class="seatorder_6">
                <input class="player_a1" placeholder="Spieler A1">
                <input class="player_a2" placeholder="Spieler A2">
                <input class="player_a3" placeholder="Spieler A3">
                <input class="player_b1" placeholder="Spieler B1">
                <input class="player_b2" placeholder="Spieler B2">
                <input class="player_b3" placeholder="Spieler B3">
            </div>
            <div class="seatorder_4">
                <input class="player_a1" placeholder="Spieler A1">
                <input class="player_a2_4" placeholder="Spieler A2">
                <input class="player_b1_4" placeholder="Spieler B1">
                <input class="player_b2_4" placeholder="Spieler B2">
            </div>
            <button type="reset">Sitzornig ändere</button>
            <button type="submit">Neui Sitzornig bestätige</button>
        </main>
        <aside class="rightside">
            <p>Folgendi Istellige gältet für di aktivi Gruppe:</p>
            <ul>
                <li>Modus: Turnier oder Cashgame</li>
                <li>(wenn Turnier)</li>
                <li>Punggt zum Turniersieg: XY Punggt</li>
                <li>Match: x Punggt</li>
                <li>Kontermatch: y Punggt</li>
                <li>Sieg: z Punggt</li>
                <li>(wenn Cashgame)</li>
                <li>Betrag pro 100 Pünggt pro Spieler: 0.5 Fr</li>
                <li>Minimumbetrag pro Spieler: vv Fr</li>
            </ul>
            <button type="reset">Istellige ändere</button>
            <button type="submit">Neui Istellige bestätige</button>
        </aside>
    </div>
    <footer>
    </footer>

</body>
</html>
