<?php
    session_start();
    require('util.php');
   
    $mysqli = setup();
    if (!isset($_SESSION['id'])) {
        exit('Gruppe kame nur als agmäldete Benutzer erstelle!');
    }
    if ( !isset($_POST['noPlayers'], $_POST['groupname'], $_POST['format']) ) {
        exit('Bitte alli Pflichtfälder usfülle!');
    }
    if (preg_match('/^[a-zA-Z0-9]+$/', $_POST['groupname']) == 0) {
        exit('Ungültigi Zeiche im Gruppename vewändet!');
    }
    $name = $_POST['groupname'];
    $noPlayers = (int) $_POST['noPlayers'];
    $istTurnier = 0;
    $tournamentPoints = 'NULL';
    $schulden = 'NULL';
    if ($_POST['format'] == 'turnier'){
        $istTurnier = 1;
        $tournamentPoints = 0;
    } elseif ($_POST['format'] == 'geld'){
        $istTurnier = 0;
        $schulden = 0;
    } else {
        exit('S Format muess entweder Gäldspiel oder Turnier si!');
    }
    $sql = "INSERT INTO Gruppen (name, istTurnier, anzahlSpieler) VALUES ('$name', $istTurnier, $noPlayers)";
    if ($mysqli->query($sql) === TRUE) {
        $groupId = mysqli_insert_id($mysqli);
        $playerId = $_SESSION['id'];
        addPlayerToGroup($playerId, $groupId);
        setActiveGroup($playerId, $groupId);
        $_SESSION['activeGroup'] = $groupId;
        if (isset($_POST['player'])){
            $arr = explode(';', $_POST['player']);
            foreach ($arr as &$value){
                $value = trim($value, " ");
                if ($value !== '') {
                    $stmt = addUserToGroupByName($value, $groupId);
                }
                
            }
        }
        $tafel = '-1 -1 -1 -1 -1 -1 -1 -1 -1 -1 -1 -1 -1 -1 -1 -1 -1 -1 -1 -1';
        $startDatum = date("d.m.Y");
        $ausgeber = rand(0, $noPlayers - 1);
        $gameId;
        if ($istTurnier == 0){
            $geld;
            if (isset($_POST['geldProPunkt']) && $_POST['geldProPunkt'] != ''){
                $geld = (float) $_POST['geldProPunkt'];
            } else {
                $geld = $defaultGeld;
            }
            if (isset($_POST['minimum']) && $_POST['minimum'] != ''){
                $minimum = (float) $_POST['minimum'];
            } else {
                $minimum = $defaultMinimum;
            }
            $sql = "INSERT INTO Spiele (startdatum, ausgeber, geld, minimum, tafel) VALUES ('$startDatum', $ausgeber, $geld, $minimum, '$tafel')";
            if ($mysqli->query($sql) === FALSE) {
                exit( "Fähler bim spiel erstelle! " . $sql . "<br>" . $mysqli->error);
            }
            $gameId = mysqli_insert_id($mysqli);
            
        } else {
            $turniersieg;
            $matsch;
            $kontermatsch;
            $sieg;
            if (isset($_POST['gewonnenBei']) && $_POST['gewonnenBei'] != ''){
                $turniersieg = (float) $_POST['gewonnenBei'];
            } else {
                $turniersieg = $defaultTurniersieg;
            }
            if (isset($_POST['punkteProMatch']) && $_POST['punkteProMatch'] != ''){
                $matsch= (float) $_POST['punkteProMatch'];
            } else {
                $matsch = $defaultMatsch;
            }
            if (isset($_POST['punkteProGegenmatch']) && $_POST['punkteProGegenmatch'] != ''){
                $kontermatsch = (float) $_POST['punkteProGegenmatch'];
            } else {
                $kontermatsch = $defaultKontermatsch;
            }
            if (isset($_POST['punkteProSieg']) && $_POST['punkteProSieg'] != ''){
                $sieg = (float) $_POST['punkteProSieg'];
            } else {
                $sieg = $defaultSieg;
            }
            $sql = "INSERT INTO Spiele (startdatum, ausgeber, matsch, turniersieg, kontermatsch, sieg, tafel) VALUES ('$startDatum', $ausgeber, $matsch, '$turniersieg', '$kontermatsch', '$sieg', '$tafel')";
            if ($mysqli->query($sql) === FALSE) {
                exit( "Fähler bim spiel erstelle! " . $sql . "<br>" . $mysqli->error);
            }
            $gameId = mysqli_insert_id($mysqli);
        }
        addGameToGroup($gameId, $groupId);
        setActiveGame($groupId, $gameId);
        $spielerA;
        $spielerB;
        if ($noPlayers == 4){
            $spielerA = "Spieler A" . '1;' . $defaultPlayerA .'2';
            $spielerB = $defaultPlayerB . '1;' . $defaultPlayerB .'2';
        } else if ($noPlayers == 6){
            $spielerA = $defaultPlayerA . '1;' . $defaultPlayerA .'2;' . $defaultPlayerA .'3';
            $spielerB = $defaultPlayerB . '1;' . $defaultPlayerB .'2;' . $defaultPlayerB .'3';
        }
        $sql = "INSERT INTO Teams (name, spieler, turnierpunkte, schulden) VALUES ('$defaultTeamA', '$spielerA', $tournamentPoints, $schulden)";
        if ($mysqli->query($sql) === FALSE) {
            exit( "Fähler bim Team erstelle! " . $sql . "<br>" . $mysqli->error);
        }
        $teamA = mysqli_insert_id($mysqli);
        $sql = "INSERT INTO Teams (name, spieler, turnierpunkte, schulden) VALUES ('$defaultTeamB', '$spielerB', $tournamentPoints, $schulden)";
        if ($mysqli->query($sql) === FALSE) {
            exit( "Fähler bim Team erstelle! " . $sql . "<br>" . $mysqli->error);
        }
        $teamB = mysqli_insert_id($mysqli);
        addTeamToGroup($teamA, $groupId);
        addTeamToGroup($teamB, $groupId);
        header('Location: ../tafel-page.php');
    } else {
        echo "Fähler: " . $sql . "<br>" . $mysqli->error;
    }
    $mysqli->close();
?>