<?php
session_start();
require('util.php');
if (!isset($_SESSION['tafel'], $_POST['id'])){
    exit('Tafelwärt het nid könne updated wärde');
}
$id = $_POST['id'];
$value = -1;
$index = (int) substr($id, 1) -1 ;
if ($id[0] == 'B'){
    $index = $index + 10;
}
$_SESSION['tafel'][$index] = $value;
setTafel();
$aDone = true;
$bDone = true;
for ($i = 0; $i < 10; $i ++){
    if ($_SESSION['tafel'][$i] == -1){
        $aDone = false;
    }
    if ($_SESSION['tafel'][$i + 10] == -1){
        $bDone = false;
    }
}
if ($aDone && $bDone){
    exit;
}
$four = array(
    2 => 0,
    1 => 2,
    3 => 1,
    0 => 3
);
$six = array(
    3 => 0,
    1 => 3,
    4 => 1,
    2 => 4,
    5 => 2,
    0 => 5
);
$converter;
if ($_SESSION['noPlayers'] == 4) {
    $converter = $four;
} else if ($_SESSION['noPlayers'] == 6){
    $converter = $six;
}
$newAusgeber = $converter[$_SESSION['ausgeber']];
if ($newAusgeber >= $_SESSION['noPlayers']/2 && $aDone){
    $newAusgeber = $converter[$newAusgeber];
} else if ($newAusgeber < $_SESSION['noPlayers']/2 && $bDone){
    $newAusgeber = $converter[$newAusgeber];
}
$_SESSION['ausgeber'] = $newAusgeber;
setAusgeber();
echo $_SESSION['players'][$newAusgeber];

?>