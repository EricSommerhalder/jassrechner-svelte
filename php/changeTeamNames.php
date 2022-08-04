<?php
session_start();
require('util.php');
if (!isset($_SESSION['activeGroup'], $_POST['teamA'], $_POST['teamB'])){
    exit('Usgwählti gruppe het nid könne updated wärde');
}

$nameA = $_POST['teamA'];
$nameB = $_POST['teamB'];
$teams = getTeams();
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
?>