<?php
session_start();
require('util.php');
if (!isset($_SESSION['activeGroup'], $_POST['name'])){
    exit('Usgwählti gruppe het nid könne updated wärde');
}
echo json_encode(getSettings($_POST['name']));
?>