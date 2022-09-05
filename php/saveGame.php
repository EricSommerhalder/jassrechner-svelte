<?php
session_start();
require('util.php');

if (!isset($_SESSION['tafel'])){
    exit('Spiel het nid könne gspeicheret wärde');
}
$isTournament = isTournamentById($_SESSION['activeGroup']);
endActiveGame();
if ($isTournament){
    $totalA = 0;
    $totalB = 0;
    $matchA = 0;
    $matchB = 0;
    $countermatchA = 0;
    $countermatchB = 0;
    for ($i = 0; $i < 10; $i++){
        if ($_SESSION['tafel'][$i] == 0){
            $countermatchB++;
        }
        if ($_SESSION['tafel'][$i + 10] == 0){
            $countermatchA++;
        }
        if ($_SESSION['tafel'][$i] == 257){
            $matchA++;
        }
        if ($_SESSION['tafel'][$i + 10] == 257){
            $matchB++;
        }
        $totalA += $_SESSION['tafel'][$i];
        $totalB += $_SESSION['tafel'][$i + 10];
    }
    updateTournamentScore($matchA, $matchB, $countermatchA, $countermatchB, $totalA, $totalB);
    createNewTournamentGame();
} else {
    $totalA = 0;
    $totalB = 0;
    for ($i = 0; $i < 10; $i++){
        $totalA += $_SESSION['tafel'][$i];
        $totalB += $_SESSION['tafel'][$i + 10];
    }
    updateCashScore($totalA, $totalB);
    createNewCashGame();
}
?>