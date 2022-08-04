<?php
session_start();
require('util.php');
if (!isset($_POST['name'])){
    exit('Gruppe het nid könne abgfrogt wärde');
}
$name = $_POST['name'];
echo isTournament($name);
?>
