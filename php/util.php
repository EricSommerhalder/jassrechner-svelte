<?php
$defaultTurniersieg = 20;
$defaultMatsch = 1;
$defaultKontermatsch = 2;
$defaultSieg = 1;
$defaultGeld = 0.1;
$defaultTeamA = 'Team A';
$defaultTeamB = 'Team B';
function setup(){
    DEFINE('DB_USERNAME', 'root');
    DEFINE('DB_PASSWORD', 'root');
    DEFINE('DB_HOST', 'localhost');
    DEFINE('DB_DATABASE', 'jass');
    DEFINE('DB_PORT', 8888);
    DEFINE('DB_SOCKET', '/Applications/MAMP/tmp/mysql/mysql.sock');

    $mysqli = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_DATABASE, DB_PORT, DB_SOCKET);

    if (mysqli_connect_error()) {
        die('Verbindigsfähler ('.mysqli_connect_errno().') '.mysqli_connect_error());
    }
    return $mysqli;
}
function getGroupByName($name){
    $mysqli = setup();
    if ($stmt = $mysqli->prepare('SELECT id, istTurnier, anzahlSpieler, aktivesSpiel FROM Gruppen WHERE name = ?')) {
        $stmt->bind_param('s', $name);
        $stmt->execute();
        $stmt->store_result();
        return $stmt;
    }
    else {
        exit('Öppis het nid funktioniert, bitte nomol probiere');
    }
}
function getUserByName($name){
    $mysqli = setup();
    if ($stmt = $mysqli->prepare('SELECT id, passwort, email, aktiveGruppe FROM Benutzer WHERE name = ?')) {
        $stmt->bind_param('s', $name);
        $stmt->execute();
        $stmt->store_result();
        return $stmt;
    }
    else {
        exit('Öppis het nid funktioniert, bitte nomol probiere');
    }
}
function getUserByEmail($email){
    $mysqli = setup();
    if ($stmt = $mysqli->prepare('SELECT id, name, passwort, aktiveGruppe FROM Benutzer WHERE email = ?')) {
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $stmt->store_result();
        return $stmt;
    }
    else {
        exit('Öppis het nid funktioniert, bitte nomol probiere');
    }
}
function addPlayerToGroup($playerId, $groupId){
    $mysqli = setup();
    $sql = "INSERT INTO Gruppen_Benutzer (benutzerId, gruppenId) VALUES ($playerId, $groupId)";
        if ($mysqli->query($sql) === TRUE) {
        } else {
            echo "Fähler: " . $sql . "<br>" . $mysqli->error;
        }
    $mysqli->close();
}
function addUserToGroupByName($name, $groupId){
    $stmt = getUserByName($name);
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $p, $e, $g);
        $stmt->fetch();
        addPlayerToGroup($id, $groupId);
    } else {
        $stmt = getUserByEmail($name);
        if ($stmt->num_rows > 0) {
            $stmt->bind_result($id, $n, $p, $g);
            $stmt->fetch();
            addPlayerToGroup($id, $groupId);
        } else {
            echo "Benutzer nid gfunde " . $name;
        }
    }
    $stmt->close();
}
function setActiveGroup($userId, $groupId) {
    $mysqli = setup();
    $sql = "UPDATE Benutzer SET aktiveGruppe=$groupId WHERE id=$userId";
    if ($mysqli->query($sql) === TRUE) {
    } else {
        echo "Nid könne update " . $mysqli->error;
    }
    $mysqli->close();
}
function addGameToGroup($gameId, $groupId){
    $mysqli = setup();
    $sql = "INSERT INTO Gruppen_Spiele (spielId, gruppenId) VALUES ($gameId, $groupId)";
        if ($mysqli->query($sql) === TRUE) {
        } else {
            echo "Fähler: " . $sql . "<br>" . $mysqli->error;
        }
    $mysqli->close();
}

function setActiveGame($groupId, $gameId) {
    $mysqli = setup();
    $sql = "UPDATE Gruppen SET aktivesSpiel=$gameId WHERE id=$groupId";
    if ($mysqli->query($sql) === TRUE) {
    } else {
        echo "Nid könne update " . $mysqli->error;
    }
    $mysqli->close();
}
function addTeamToGroup($teamId, $groupId){
    $mysqli = setup();
    $sql = "INSERT INTO Gruppen_Teams (teamId, gruppenId) VALUES ($teamId, $groupId)";
        if ($mysqli->query($sql) === TRUE) {
        } else {
            echo "Fähler: " . $sql . "<br>" . $mysqli->error;
        }
    $mysqli->close();
}
?>