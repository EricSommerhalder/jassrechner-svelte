<?php
session_start();
require('util.php');
if (!isset($_SESSION['activeGroup'])){
    exit('Usgwählti gruppe het nid könne updated wärde');
}
echo json_encode(getPlayerNames());
?>