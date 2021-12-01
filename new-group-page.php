<?php
// We need to use sessions, so you should always start sessions using the below code.
session_start();
// If the user is not logged in redirect to the login page...
if (!isset($_SESSION['id'])) {
	header('Location: login-page.php');
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
    <link rel="stylesheet" href="css/seatorder.css">
  </head>
    <script type="text/javascript">
        function formatCheck(){
            if (document.getElementById('turnier').checked){
                document.getElementById('turnierInfo').style.display = 'block';
            } else {
                document.getElementById('turnierInfo').style.display = 'none';
            }
            if (document.getElementById('geld').checked){
                document.getElementById('geldInfo').style.display = 'block';
            } else {
                document.getElementById('geldInfo').style.display = 'none';
            }
        }
    </script>
    <form action="php/createGroup.php" method="post">
        <input placeholder="Gruppename" name="groupname"/>
        <p>Wieviel jasse mit?</p>
        <input type="radio" id="four" name="noPlayers" value="4">
        <label for="four">4 Spieler</label><br>
        <input type="radio" id="six" name="noPlayers" value="6">
        <label for="six">6 Spieler</label><br>
        <p>Weles Format jassedr?</p>
        <input type="radio" onclick="javascript:formatCheck();" id="turnier" name="format" value="turnier">
        <label for="turnier">Turnier</label><br>
        <input type="radio" onclick="javascript:formatCheck();" id="geld" name="format" value="geld">
        <label for="geld">Um Gäld</label><br>
        <div id="turnierInfo" style="display:none">
            <input placeholder="Wieviel Pünggt bruuchts zum gwünne?" name="gewonnenBei"/>
            <input placeholder="Wieviel Pünggt gits pro Match?" name="punkteProMatch"/>
            <input placeholder="Wieviel Pünggt gits pro Gegematch?" name="punkteProGegenmatch"/>
            <input placeholder="Wieviel Pünggt gits füre Sieg" name="punkteProSieg"/>
        </div>
        <div id="geldInfo" style="display:none">
            <input placeholder="Gäld pro Punggt" name="geldProPunkt"/>
        </div>
        <p>Spieler hinzuefiege. Mehreri durch ; trenne</p>
        <input placeholder="Spieler" name="player"/> 
        <input class="submitbtn" type="submit" value="Gruppe erstelle">
</form>
    

</html>