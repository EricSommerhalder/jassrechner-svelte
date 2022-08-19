<?php
session_start();
require('util.php');
if (!isset($_SESSION['tafel'], $_POST['id'], $_POST['value'])){
    exit('Tafelwärt het nid könne updated wärde');
}
$id = $_POST['id'];
$value = $_POST['value'];
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
    0 => 2,
    2 => 1,
    1 => 3,
    3 => 0,
);
$six = array(
    0 => 3,
    3 => 1,
    1 => 4,
    4 => 2,
    2 => 5,
    5 => 0
);
$converter;
if ($_SESSION['noPlayers'] == 4) {
    $converter = $four;
} else if ($_SESSION['noPlayers'] == 6){
    $converter = $six;
}
$newAusgeber = $converter[$_SESSION['ausgeber']];
if ($newAusgeber >= $_SESSION['noPlayers']/2 && $bDone){
    $newAusgeber = $converter[$newAusgeber];
} else if ($newAusgeber < $_SESSION['noPlayers']/2 && $aDone){
    $newAusgeber = $converter[$newAusgeber];
}
$_SESSION['ausgeber'] = $newAusgeber;
setAusgeber();
echo $_SESSION['players'][$newAusgeber];

?>