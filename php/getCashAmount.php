<?php
session_start();
require('util.php');
if (!isset($_SESSION['activeGroup'], $_POST['diff'])){
    exit('Öppis is schief gange!');
}
$diff = $_POST['diff'];
$dets = getCashDetails($_SESSION['activeGroup']);
$total = $diff * $dets[0] / 100;
if ($total < $dets[1]){
    echo $dets[1];
}
else {
    echo $total;
}
?>