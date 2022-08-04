<?php
session_start();
require('util.php');
if (!isset($_SESSION['activeGroup'], $_POST['settings'], $_POST['old'])){
    exit('Usgwählti gruppe het nid könne updated wärde');
}
$settings = explode(',', $_POST['settings']);
$game = getActiveGame($_SESSION['activeGroup']);
$arr = [];
$old = explode(',', $_POST['old']);
foreach ($old as &$setting){
    $temp = (float) $setting;
    if ($temp <= 0){
        $temp = 'NULL';
    }
    array_push($arr, $temp);
}
$old = $arr;
$arr = [];
foreach ($settings as $key=>$setting){
    $temp = (float) $setting;
    if ($temp <= 0){
        $temp = $old[$key];
    }
    array_push($arr, $temp);
}
$settings = $arr;
$mysqli = setup();
$sql = "UPDATE Spiele SET turniersieg = $settings[0], matsch = $settings[1], kontermatsch = $settings[2], sieg = $settings[3], geld = $settings[4], minimum = $settings[5] WHERE id=$game";
if ($mysqli->query($sql) === TRUE) {
} else {
    echo "Nid könne update" . $mysqli->error;
}
$mysqli->close();
?>