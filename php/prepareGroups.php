<?php
session_start();
function prep(){
    require_once('util.php');
    return getGroupNames(getGroups());  
}

?>