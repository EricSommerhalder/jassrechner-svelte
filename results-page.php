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

<body>
    <nav>
        <ul>
            <li><a href="tafel-page.php">Tafel</a></li>
            <li>|</li>
            <li class="aktiv"><a href="results-page.php">Resultate</a></li>
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
    <div class="results">
        <h2>Turnier</h2>
        <table>
            <tr>
                <td></td>
                <td>Teamname A</td>
                <td>Pünggt</td>
                <td>Teamname B</td>
                <td>Pünggt</td>
                <td>Agfange am</td>
                <td>Beändet am</td>
                <td>Notize</td>
            </tr>
            <tr>
                <td>Turnier 1</td>
                <td>Männer</td>
                <td>5</td>
                <td>Fraue</td>
                <td>2</td>
                <td>1.1.2022</td>
                <td></td>
                <td></td>
            </tr>
        </table>
        <h2>Cash</h2>
        <table>
            <tr>
                <td></td>
                <td>Teamname A</td>
                <td>Teamname B</td>
                <td>gspiielt am</td>
                <td>Verliererteam</td>
                <td>zum zahle</td>
            </tr>
            <tr>
                <td>Spiel 1</td>
                <td>E & A</td>
                <td>H & C</td>
                <td>7.9.22</td>
                <td>E & A</td>
                <td>22.-</td>
            </tr>
        </table>
    </div>

</body>

</html>