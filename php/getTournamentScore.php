<?php
session_start();
require('util.php');
if (!isset($_SESSION['activeGroup'])){
    exit('Öppis isch schief gange, bitte refreshe');
}
echo json_encode(getTournamentScore($_SESSION['activeGroup']));
?>