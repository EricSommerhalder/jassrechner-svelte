<?php
session_start();
require('util.php');
function prep(){
    $res = getGroups();
    $s = "";
    foreach ($res as &$val){
        $s .= $val;
        $s .= "\n";
    }
    return $s;
}

?>