<?php
session_start();
require('util.php');
if (!isset($_SESSION['id'], $_POST['name'])){
    exit('Usgwählti gruppe het nid könne updated wärde');
}

$id = $_SESSION['id'];
$name = $_POST['name'];
$group = getGroupByName($name);
$active = getActiveGroup($id);
if ($group == $active){
    exit ("Aktivi Gruppe ka nid glöscht wärde. Bitte zerst e anderi Gruppe awähle.");
} else {
    $mysqli = setup();
    $sql = "DELETE FROM Gruppen WHERE id=$group";
    if ($mysqli->query($sql) === TRUE) {
    } else {
        echo "Gruppe het nid könne glöscht wärde " . $mysqli->error;
    }
    $sql = "DELETE FROM Gruppen_Benutzer WHERE gruppenId=$group";
    if ($mysqli->query($sql) === TRUE) {
    } else {
        echo "Gruppe het nid vom Benutzer entfärnt könne wärde " . $mysqli->error;
    }
    $games = getGames($group);
    $sql = "DELETE FROM Gruppen_Spiele WHERE gruppenId=$group";
    if ($mysqli->query($sql) === TRUE) {
    } else {
        echo "Gruppe het nid vom Spiel könne trennt wärde " . $mysqli->error;
    }
    foreach ($games as &$game){
        $sql = "DELETE FROM Spiele WHERE id=$game";
        if ($mysqli->query($sql) === TRUE) {
        } else {
            echo "Spiel het nid könne glöscht wärde " . $mysqli->error;
        }
    }
    $teams = getTeamsById($group);
    $sql = "DELETE FROM Gruppen_Teams WHERE gruppenId=$group";
    if ($mysqli->query($sql) === TRUE) {
    } else {
        echo "Gruppe het nid vom Team könne trennt wärde " . $mysqli->error;
    }
    foreach ($teams as &$team){
        $sql = "DELETE FROM Teams WHERE id=$team";
        if ($mysqli->query($sql) === TRUE) {
        } else {
            echo "Team het nid könne glöscht wärde " . $mysqli->error;
        }
    }
    $mysqli->close();
    
}
?>