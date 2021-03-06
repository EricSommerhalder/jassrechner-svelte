<?php
require ('config.php');
$defaultTurniersieg = 21;
$defaultMatsch = 1;
$defaultKontermatsch = 2;
$defaultSieg = 1;
$defaultGeld = 0.1;
$defaultTeamA = 'Team A';
$defaultTeamB = 'Team B';
$defaultPlayerA = 'Spieler A';
$defaultPlayerB = 'Spieler B';

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
function getUserById($id){
    $mysqli = setup();
    if ($stmt = $mysqli->prepare('SELECT name, passwort, email, aktiveGruppe FROM Benutzer WHERE id = ?')) {
        $stmt->bind_param('s', $id);
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
function getActiveGroup($userId) {
    $stmt = getUserById($userId);
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($n, $p, $e, $group);
        $stmt->fetch();
        $_SESSION['activeGroup'] = $group;
        return $group;
    }
    return NULL;
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
function getActiveGame($groupId){
    $mysqli = setup();
    if ($stmt = $mysqli->prepare('SELECT aktivesSpiel FROM Gruppen WHERE id = ?')) {
        $stmt->bind_param('s', $groupId);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $stmt->bind_result($game);
            $stmt->fetch();
            $_SESSION['activeGame'] = $game;
            return $game;
        }
        return NULL;
    }
    else {
        exit('Öppis het nid funktioniert, bitte nomol probiere');
    }
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

function getAusgeber(){
    $mysqli = setup();
    if ($stmt = $mysqli->prepare('SELECT ausgeber FROM Spiele WHERE id = ?')) {
        $stmt->bind_param('s', $_SESSION['activeGame']);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $stmt->bind_result($ausgeber);
            $stmt->fetch();
            $_SESSION['ausgeber'] = $ausgeber;
        }
    }
    else {
        exit('Öppis het nid funktioniert, bitte nomol probiere');
    }
}
function getNoPlayers(){
    $mysqli = setup();
    if ($stmt = $mysqli->prepare('SELECT anzahlSpieler FROM Gruppen WHERE id = ?')) {
        $stmt->bind_param('s', $_SESSION['activeGroup']);
        
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $stmt->bind_result($amount);
            $stmt->fetch();
            $_SESSION['noPlayers'] = $amount;
        }
    }
    else {
        exit('Öppis het nid funktioniert, bitte nomol probiere');
    }
}
function getGroupName($groupId){
    $mysqli = setup();
    if ($stmt = $mysqli->prepare('SELECT name FROM Gruppen WHERE id = ?')) {
        $stmt->bind_param('s', $groupId);
        
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $stmt->bind_result($name);
            $stmt->fetch();
            return $name;
        }
    }
    else {
        exit('Öppis het nid funktioniert, bitte nomol probiere');
    }
}
function getGroups(){
    $mysqli = setup();
    if ($stmt = $mysqli->prepare('SELECT (gruppenId) FROM `Gruppen_Benutzer` WHERE benutzerId = ? ORDER by gruppenId ASC')) {
        $stmt->bind_param('s', $_SESSION['id']);   
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $stmt->bind_result($temp);
            $gruppenIds = [];
            for ($i = 0; $i < $stmt->num_rows; $i++){
                $stmt->fetch();
                array_push($gruppenIds, $temp);
            }
            return $gruppenIds;
        }
    } else {
        exit('Öppis het nid funktioniert, bitte nomol probiere');
    }

}
function getGroupNames($groupIds){
    $toReturn = [];
    for ($i = 0; $i < count($groupIds); $i++){
        arrary_push($toReturn, getGroupName[$groupIds[$i]]);
    }
    return $toReturn;
}
function getTeams(){
    $mysqli = setup();
    if ($stmt = $mysqli->prepare('SELECT (teamId) FROM `Gruppen_Teams` WHERE gruppenId = ? ORDER by teamId ASC')) {
        $stmt->bind_param('s', $_SESSION['activeGroup']);   
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $stmt->bind_result($temp);
            $teamIds = [];
            $stmt->fetch();
            array_push($teamIds, $temp);
            $stmt->fetch();
            array_push($teamIds, $temp);
            return $teamIds;
        }
    } else {
        exit('Öppis het nid funktioniert, bitte nomol probiere');
    }
}
function getPlayerNames(){
            $teamIds = getTeams();
            $arr = [];
            foreach ($teamIds as &$id){
                $arr = array_merge($arr, getPlayerNamesOfTeam($id));
            }
            $_SESSION['players'] = $arr;  
}
function getPlayerNamesOfTeam($teamId){
    $mysqli = setup();
    if ($stmt = $mysqli->prepare('SELECT spieler FROM Teams WHERE id = ?')) {
        $stmt->bind_param('s', $teamId);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $stmt->bind_result($spieler);
            $stmt->fetch();
            return explode(';', $spieler);
        }
    }
    else {
        exit('Öppis het nid funktioniert, bitte nomol probiere');
    }
}
function getTeamNames(){
    $teamIds = getTeams();
    $arr = [];
    foreach ($teamIds as &$id){
        array_push($arr, getTeamName($id));
    }
    $_SESSION['teamnames'] = $arr;
}
function getTeamName($id){
    $mysqli = setup();
    if ($stmt = $mysqli->prepare('SELECT name FROM Teams WHERE id = ?')) {
        $stmt->bind_param('s', $id);
        
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $stmt->bind_result($name);
            $stmt->fetch();
            return $name;
        }
    }
    else {
        exit('Öppis het nid funktioniert, bitte nomol probiere');
    }
}
function getTafel(){
    $mysqli = setup();
    if ($stmt = $mysqli->prepare('SELECT tafel FROM Spiele WHERE id = ?')) {
        $stmt->bind_param('s', $_SESSION['activeGame']);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $stmt->bind_result($tafel);
            $stmt->fetch();
            $arr = explode(' ', $tafel);
            $intarr = [];
            foreach($arr as $a){
                array_push($intarr, (int) $a);
            }
            $_SESSION['tafel'] = $intarr;
        }
    }
    else {
        exit('Öppis het nid funktioniert, bitte nomol probiere');
    }
}
function setTafel(){
        $tafel = implode(" ", $_SESSION['tafel']);
        $activeGame = $_SESSION['activeGame'];
        $mysqli = setup();
        $sql = "UPDATE Spiele SET tafel='$tafel' WHERE id=$activeGame";
        if ($mysqli->query($sql) === TRUE) {
        } else {
            echo "Nid könne update " . $mysqli->error;
        }
        $mysqli->close();
}
function setAusgeber(){
    $ausgeber = $_SESSION['ausgeber'];
    $activeGame = $_SESSION['activeGame'];
    $mysqli = setup();
    $sql = "UPDATE Spiele SET ausgeber='$ausgeber' WHERE id=$activeGame";
    if ($mysqli->query($sql) === TRUE) {
    } else {
        echo "Nid könne update " . $mysqli->error;
    }
    $mysqli->close();
}
?>
