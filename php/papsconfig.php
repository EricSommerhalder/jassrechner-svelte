<?php
function setup(){
    DEFINE('DB_USERNAME', 'root');
    DEFINE('DB_PASSWORD', '');
    DEFINE('DB_HOST', 'localhost');
    DEFINE('DB_DATABASE', 'jass');
    $mysqli = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_DATABASE);

    if (mysqli_connect_error()) {
        die('Verbindigsfähler ('.mysqli_connect_errno().') '.mysqli_connect_error());
    }
    return $mysqli;
}
?>