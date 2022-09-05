<?php
session_start();
require('util.php');
if (!isset($_SESSION['activeGroup'], $_POST['teamA'], $_POST['teamB'])){
    exit('Usgwählti gruppe het nid könne updated wärde');
}

$nameA = $_POST['teamA'];
$nameB = $_POST['teamB'];
$teams = getTeams();
$oldA = getTeamName($teams[0]);
$oldB = getTeamName($teams[1]);
$mysqli = setup();
$sql = "UPDATE Teams SET name='$nameA' WHERE id=$teams[0]";
if ($mysqli->query($sql) === TRUE) {
} else {
    echo "Nid könne update" . $mysqli->error;
}
$sql = "UPDATE Teams SET name='$nameB' WHERE id=$teams[1]";
if ($mysqli->query($sql) === TRUE) {
} else {
    echo "Nid könne update" . $mysqli->error;
}
$mysqli->close();

$players = getPlayerNames();
$playerStringA = "";
$playerStringB = "";
for ($i = 0; $i < sizeof($players)/2; $i++){
    $playerA = str_replace("Spieler ", "", $players[$i]);
    $playerB = str_replace("Spieler ", "", $players[$i + sizeof($players)/2]);
    $comparisonStringA = str_replace("Team ", "", $oldA) . strval($i + 1);
    $comparisonStringB = str_replace("Team ", "", $oldB) . strval($i + 1);
    if (str_replace(" ", "", $playerA) == str_replace(" ", "", $comparisonStringA) && $oldA != $nameA){
        $playerStringA .= $nameA . " " . strval($i + 1) . ";";
    } else {
        $playerStringA .= $players[$i] . ";";
    }
    if (str_replace(" ", "", $playerB) == str_replace(" ", "", $comparisonStringB) && $oldB != $nameB){
        $playerStringB .= $nameB . " " . strval($i + 1) . ";";
    } else {
        $playerStringB .= $players[$i + sizeof($players)/2] . ";";
    }
}
    $playerStringA = substr_replace($playerStringA ,"", -1);
    $playerStringB = substr_replace($playerStringB ,"", -1);

    $mysqli = setup();
    $sql = "UPDATE Teams SET spieler='$playerStringA' WHERE id=$teams[0]";
    if ($mysqli->query($sql) === TRUE) {
    } else {
        echo "Nid könne update" . $mysqli->error;
    }
    $sql = "UPDATE Teams SET spieler='$playerStringB' WHERE id=$teams[1]";
    if ($mysqli->query($sql) === TRUE) {
    } else {
        echo "Nid könne update" . $mysqli->error;
    }
    $mysqli->close();
    echo json_encode(array_merge(explode(";", $playerStringA), explode(";", $playerStringB)));
?>