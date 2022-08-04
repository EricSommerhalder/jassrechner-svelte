<?php
session_start();
require('util.php');
if (!isset($_SESSION['id'], $_POST['name'])){
    exit('Usgwählti gruppe het nid könne updated wärde');
}

$id = $_SESSION['id'];
$name = $_POST['name'];
$group = getGroupByName($name);
setActiveGroup($id, $group);
$_SESSION['activeGroup'] = $group;
echo getNoPlayersByName($name);
?>