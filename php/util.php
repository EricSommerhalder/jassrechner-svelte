<?php
require ('config.php');
$defaultTurniersieg = 21;
$defaultMatsch = 1;
$defaultKontermatsch = 2;
$defaultSieg = 1;
$defaultGeld = 0.1;
$defaultMinimum = 0.5;
$defaultTeamA = 'Team A';
$defaultTeamB = 'Team B';
$defaultPlayerA = 'Spieler A';
$defaultPlayerB = 'Spieler B';

function getGroupByName($name){
    $mysqli = setup();
    if ($stmt = $mysqli->prepare('SELECT id FROM Gruppen WHERE name = ?')) {
        $stmt->bind_param('s', $name);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $stmt->bind_result($id);
            $stmt->fetch();
            return $id;
        }
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
    getActiveGroup($userId);
    getNoPlayers();
    getPlayerNames();
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
function getNoPlayersByName($name){
    $mysqli = setup();
    if ($stmt = $mysqli->prepare('SELECT anzahlSpieler FROM Gruppen WHERE name = ?')) {
        $stmt->bind_param('s', $name);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $stmt->bind_result($amount);
            $stmt->fetch();
            return $amount;
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

function getGames($groupId){
    $mysqli = setup();
    if ($stmt = $mysqli->prepare('SELECT (spielId) FROM `Gruppen_Spiele` WHERE gruppenId = ? ORDER by gruppenId ASC')) {
        $stmt->bind_param('s', $groupId);   
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $stmt->bind_result($temp);
            $spieleIds = [];
            for ($i = 0; $i < $stmt->num_rows; $i++){
                $stmt->fetch();
                array_push($spieleIds, $temp);
            }
            return $spieleIds;
        }
    } else {
        exit('Öppis het nid funktioniert, bitte nomol probiere');
    }
}
function getGroupNames(){
    $toReturn = [];
    $arr = getGroups();
    for ($i = 0; $i < count($arr); $i++){
        array_push($toReturn, getGroupName($arr[$i]));
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
function getTeamsById($groupId){
    $mysqli = setup();
    if ($stmt = $mysqli->prepare('SELECT (teamId) FROM `Gruppen_Teams` WHERE gruppenId = ? ORDER by teamId ASC')) {
        $stmt->bind_param('s', $groupId);   
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
            return $arr;  
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
    return $arr;
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

function isTournament($name){
    $group = getGroupByName($name);
    $mysqli = setup();
    if ($stmt = $mysqli->prepare('SELECT istTurnier FROM Gruppen WHERE id = ?')) {
        $stmt->bind_param('s', $group);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $stmt->bind_result($re);
            $stmt->fetch();
            return $re;
        }
    }
    else {
        exit('Öppis het nid funktioniert, bitte nomol probiere');
    }
}
function isTournamentById($id){
    $mysqli = setup();
    if ($stmt = $mysqli->prepare('SELECT istTurnier FROM Gruppen WHERE id = ?')) {
        $stmt->bind_param('s', $id);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $stmt->bind_result($re);
            $stmt->fetch();
            return $re;
        }
    }
    else {
        exit('Öppis het nid funktioniert, bitte nomol probiere');
    }
}
function getSettings($name){
    $group = getGroupByName($name);
    $game = getActiveGame($group);
    $keys = ['turniersieg', 'matsch', 'kontermatsch', 'sieg', 'geld', 'minimum'];
    $arr = [];
    foreach ($keys as &$key){
        array_push($arr, getSettingsVal($game, $key));
    }
    return $arr; 
}

function getSettingsVal($game, $key){
    $mysqli = setup();
    if ($stmt = $mysqli->prepare('SELECT ' . $key . ' FROM Spiele WHERE id = ?')) {
        $stmt->bind_param('s', $game);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $stmt->bind_result($re);
            $stmt->fetch();
            return $re;
        }
    }
    else {
        exit('Öppis het nid funktioniert, bitte nomol probiere' . $mysqli -> error);
    }
}

function getCashDetails($group){
    $game = getActiveGame($group);
    $arr = [];
    $keys = ['geld', 'minimum'];
    foreach ($keys as &$key){
        array_push($arr, getSettingsVal($game, $key));
    }
    return $arr;
}

function getCashScores($group){
    $teams = getTeamsById($group);
    $arr = [];
    for ($i = 0; $i < 2; $i ++){
        $team = $teams[$i];
        $mysqli = setup();
        if ($stmt = $mysqli->prepare('SELECT schulden FROM Teams WHERE id = ?')) {
            $stmt->bind_param('s', $team);
            $stmt->execute();
            $stmt->store_result();
            if ($stmt->num_rows > 0) {
                $stmt->bind_result($re);
                $stmt->fetch();
                array_push($arr, $re);
            }
        }
        else {
            exit('Öppis het nid funktioniert, bitte nomol probiere' . $mysqli -> error);
        }
    }
    return $arr;
}
function getTournamentPoints($team){
    $mysqli = setup();
    if ($stmt = $mysqli->prepare('SELECT turnierpunkte FROM Teams WHERE id = ?')) {
        $stmt->bind_param('s', $team);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $stmt->bind_result($re);
            $stmt->fetch();
            return $re;
        }
    }
    else {
        exit('Öppis het nid funktioniert, bitte nomol probiere' . $mysqli -> error);
    }
}
function getTournamentScore($group){
    $game = getActiveGame($group);
    $arr = [];
    $teams = getTeamsById($group);
    foreach ($teams as &$team){
        array_push($arr, getTournamentPoints($team));
    }
    $keys = ['matsch', 'kontermatsch'];
    foreach ($keys as &$key){
        array_push($arr, getSettingsVal($game, $key));
    }
    return $arr;
}

function endActiveGame(){
    $game = $_SESSION['activeGame'];
    $val = date("d.m.Y");
    $mysqli = setup();
    $sql = "UPDATE Spiele SET enddatum='$val' WHERE id=$game";
    if ($mysqli->query($sql) === TRUE) {
    } else {
        echo "Nid könne update " . $mysqli->error;
    }
    $mysqli->close();
}

function updateTournamentScore($matchA, $matchB, $countermatchA, $countermatchB){
    $dets = getTournamentScore($_SESSION['activeGroup']);
    $toAddA = $matchA * $dets[2] + $countermatchA * $dets[3];
    $toAddB = $matchB * $dets[2] + $countermatchB * $dets[3];
    $teams = getTeams();
    $oldValA = $dets[0];
    $oldValB = $dets[1];
    $toAddA+= $oldValA;
    $toAddB+= $oldValB;
    setTournamentScore($teams[0], $toAddA);
    setTournamentScore($teams[1], $toAddB);
}

function setTournamentScore($team, $score){
    $mysqli = setup();
    $sql = "UPDATE Teams SET turnierpunkte=$score WHERE id=$team";
    if ($mysqli->query($sql) === TRUE) {
    } else {
        echo "Nid könne update " . $mysqli->error . $sql;
    }
    $mysqli->close();
}

function updateCashScore($totalA, $totalB){
    $dets = getCashDetails($_SESSION['activeGroup']);
    $diff = abs($totalA - $totalB);
    $val = $diff * $dets[0] / 100;
    if ($val < $dets[1]){
        $val = $dets[1];
    }
    $teams = getTeams();
    $schulden = getCashScores($_SESSION['activeGroup']);
    if ($totalA > $totalB){
        setCashScore($teams[1], $val + $schulden[1]);
    } else if ($totalB > $totalA) {
        setCashScore($teams[0], $val + $schulden[0]);
    }
}
function setCashScore($team, $val){
    $mysqli = setup();
    $sql = "UPDATE Teams SET schulden=$val WHERE id=$team";
    if ($mysqli->query($sql) === TRUE) {
    } else {
        echo "Nid könne update " . $mysqli->error;
    }
    $mysqli->close();
}
function createNewTournamentGame(){
    $tafel = '-1 -1 -1 -1 -1 -1 -1 -1 -1 -1 -1 -1 -1 -1 -1 -1 -1 -1 -1 -1';
    $startDatum = date("d.m.Y");
    $ausgeber = rand(0, $_SESSION['noPlayers'] - 1);
    $mysqli = setup();
    $sql = "SELECT turniersieg, matsch, kontermatsch, sieg FROM Spiele WHERE id = ?";
    if ($stmt = $mysqli->prepare($sql)) {
        $stmt->bind_param('s', $_SESSION['activeGame']);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $stmt->bind_result($oldT, $oldM, $oldC, $oldS);
            $stmt->fetch();
        }
    }
    else {
        exit('Öppis het nid funktioniert, bitte nomol probiere');
    }
    $sql = "INSERT INTO Spiele (startdatum, turniersieg, matsch, kontermatsch, sieg, ausgeber, tafel) VALUES ('$startDatum', $oldT, $oldM, $oldC, $oldS, $ausgeber, '$tafel')";
    echo $sql . "\n";
    if ($mysqli->query($sql) === FALSE) {
        exit( "Fähler bim spiel erstelle! " . $sql . "<br>" . $mysqli->error);
    }
    $gameId = mysqli_insert_id($mysqli);
    addGameToGroup($gameId, $_SESSION['activeGroup']);
    setActiveGame($_SESSION['activeGroup'], $gameId);
    getActiveGame($_SESSION['activeGroup']);
}
function createNewCashGame(){
    $tafel = '-1 -1 -1 -1 -1 -1 -1 -1 -1 -1 -1 -1 -1 -1 -1 -1 -1 -1 -1 -1';
    $startDatum = date("d.m.Y");
    $ausgeber = rand(0, $_SESSION['noPlayers'] - 1);
    $mysqli = setup();
    $sql = "SELECT geld, minimum FROM Spiele WHERE id = ?";
    if ($stmt = $mysqli->prepare($sql)) {
        $stmt->bind_param('s', $_SESSION['activeGame']);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $stmt->bind_result($oldG, $oldMi);
            $stmt->fetch();
        }
    }
    else {
        exit('Öppis het nid funktioniert, bitte nomol probiere');
    }
    $sql = "INSERT INTO Spiele (startdatum, geld, minimum, ausgeber, tafel) VALUES ('$startDatum', $oldG, $oldMi, $ausgeber, '$tafel')";
    $sql = str_replace(", ,", ", NULL,", $sql);
    echo $sql . "\n";
    if ($mysqli->query($sql) === FALSE) {
        exit( "Fähler bim spiel erstelle! " . $sql . "<br>" . $mysqli->error);
    }
    echo "save passed\n";
    $gameId = mysqli_insert_id($mysqli);
    addGameToGroup($gameId, $_SESSION['activeGroup']);
    setActiveGame($_SESSION['activeGroup'], $gameId);
    getActiveGame($_SESSION['activeGroup']);
}
?>
