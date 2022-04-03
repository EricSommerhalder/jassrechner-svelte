<?php
session_start();
function prep(){
    require_once('util.php');
    $res = getGroups();
    $s = "";
    foreach ($res as &$val){
        $s .= $val;
        $s .= "\n";
    }
    return $s;
}

?>