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

?>