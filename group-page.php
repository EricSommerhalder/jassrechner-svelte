<?php
//TODO: disallow same group names!
//TODO: Onload check currently active group!
// We need to use sessions, so you should always start sessions using the below code.
session_start();
// If the user is not logged in redirect to the login page...
require('php/util.php');
if (!isset($_SESSION['id'])) {
	header('Location: login-page.php');
	exit;
}
if (!isset($_SESSION['activeGroup'])) {
    getActiveGroup($_SESSION['id']);
    if (!isset($_SESSION['activeGroup'])){
        header('Location: new-group-page.php');
    }
}
if (!isset ($_SESSION['noPlayers'])) {
    getNoPlayers();
}  
?>
<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jassrechner | Group</title>
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/styles.css ">
    <link rel="stylesheet" href="css/seatorder.css">
    <link rel="stylesheet" href="css/checkbox.css">
</head>
<body>
    <nav>
        <ul>
            <li><a href="tafel-page.php">Tafel</a></li>
            <li>|</li>
            <li><a href="results-page.php">Resultate</a></li>
            <li>|</li>
            <li><a href="group-page.php">Gruppe</a></li>
            <li>|</li>

            <li><a href="user-page.php">Benutzer</a></li>
            <li>|</li>
            <li><a href="php/logout.php">Abmelden</a></li>
        </ul>
        <p class="crumb"><?=$_SESSION['name']?>, Du bisch igloggt</p>
        <p class="crumb" id="activeGroupText">Aktivi Gruppe: <?php echo getGroupName($_SESSION['activeGroup'])?></p>
    </nav>
    <div class="content">
        <aside class="leftside">

            <aside id="res">       
            </aside>
            <a href="new-group-page.php">
                <button type="submit">Neui Gruppe erstelle</button>
            </a>
        </aside>
        <main>
            <h2>Di aktive Gruppe:</h2>
            <h3>Teamnamen:</h3>
            <div id="teamnames" class="teamname">
            </div>
            <div id="teamnamesbutton"></div>
            <h3>Sitzordnung:</h3>
            <div id="table"></div>
            <div id="tablebutton"></div>
        </main>
        <aside class="rightside" id="settings">
            <button type="reset">Istellige ändere</button>
            <button type="submit">Neui Istellige bestätige</button>
        </aside>
    </div>
    <footer>
    </footer>
    <script>
    var deletedGroups = [];
    function loadGroupList() {
        groups = <?php echo json_encode(getGroupNames())?>;
        daddy = document.getElementById("res");
        daddy.innerHTML = '';
        for (group of groups){
            if (deletedGroups.indexOf(group) == -1){
                newDiv = document.createElement("div");
                newDiv.className = "groupsection";
                newLabel = document.createElement("label");
                newLabel.className = "selectionBtn";
                newLabel.innerText = group;

                newInput = document.createElement("input");
                newInput.type = "radio";
                newInput.name = "gruppenwahl";
                newInput.setAttribute("onclick", 'myFunction("' + group + '")');
                if (deletedGroups.length == 0 && group == '<?php echo getGroupName($_SESSION['activeGroup'])?>'){
                    newInput.checked = true;
                }
                
                newLabel.appendChild(newInput);

                newSpan = document.createElement("span");
                newSpan.className = "checkmark";
                newLabel.appendChild(newSpan);

                newButton = document.createElement("a");
                newButton.className = "deleteGroup";
                newButton.innerHTML =  "Die Gruppe lösche";
                newButton.setAttribute("onclick", 'deleteGroup("' + group + '")')
                
                newDiv.appendChild(newLabel);
                newDiv.appendChild(newButton);
                daddy.appendChild(newDiv);
            }
        }
    }
    function deleteGroup(group){
        fetch('php/deleteGroup.php', {
            method: 'POST',
            headers: {
                "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8",
            },
            body: `name=${group}`,
        }).then((response) => deletedGroups.push(group)).then((res)=> loadGroupList());
    }
    function setActiveGroupText(name){
        daddy = document.getElementById("activeGroupText");
        val = "Aktivi Gruppe: " + name;
        daddy.innerHTML = val;
    }
    function myFunction(s) {
        fetch('php/setActiveGroupByName.php', {
            method: 'POST',
            headers: {
                "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8",
            },
            body: `name=${s}`,
        }).then((response) => response.text())
            .then((res) => ( loadMid(parseInt(res), false)));
        setActiveGroupText(s);
        loadSettings(s, false);
    }
    function loadMid(noOfPlayers, isActive){
        loadTable(noOfPlayers, isActive);
        loadTeamNames(isActive);
    }
    function loadTeamNames(isActive){
        fetch('php/getTeamNames.php', {
            method: 'POST',
            headers: {
                "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8",
            },
        }).then((response) => response.text())
            .then((res) => (buildTeamNames(JSON.parse(res), isActive)));
    }
    function buildTeamNames(teamNames, isActive){
        daddy = document.getElementById("teamnames");
        daddy.innerHTML = '';
        daddy.appendChild(inputHelper("teamName", teamNames[0], isActive));
        daddy.appendChild(inputHelper("teamName", teamNames[1], isActive));

        daddy = document.getElementById("teamnamesbutton");
        daddy.innerHTML = '';
        b = document.createElement("button");
        if (isActive){
            b.type = "submit";
            b.innerHTML = "Neui Teamname bestätige";
            b.addEventListener("click", function(){
                changeTeamNames(teamNames);
                buildTeamNames(teamNames, false);
                });
            b2 = document.createElement("button");
            b2.type = "reset";
            b2.innerHTML = "Abbräche";
            b2.addEventListener("click", function(){
                buildTeamNames(teamNames, false);
                });
            daddy.appendChild(b2);
        }
        if (!isActive){
            b.type = "reset";
            b.innerHTML = "Teamname ändere";
            b.addEventListener("click", function(){
                buildTeamNames(teamNames, true);
                });
        }
        daddy.appendChild(b);
    }
    function loadTable(noOfPlayers, isActive){
        fetch('php/getPlayerNames.php', {
            method: 'POST',
            headers: {
                "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8",
            },
        }).then((response) => response.text())
            .then((res) => (buildTable(noOfPlayers, JSON.parse(res), isActive)));
    }
    function buildTable(noOfPlayers, res, isActive){
        daddy = document.getElementById("table");
                daddy.innerHTML = '';
                if (noOfPlayers == 4){
                    daddy.className = "seatorder_4";
                    daddy.appendChild(inputHelper("player_a1", res[0], isActive));
                    daddy.appendChild(inputHelper("player_a2_4", res[1], isActive));
                    daddy.appendChild(inputHelper("player_b1_4", res[2], isActive));
                    daddy.appendChild(inputHelper("player_b2_4", res[3], isActive));
                }
                if (noOfPlayers == 6){
                    daddy.className = "seatorder_6";
                    daddy.appendChild(inputHelper("player_a1", res[0], isActive));
                    daddy.appendChild(inputHelper("player_a2", res[1], isActive));
                    daddy.appendChild(inputHelper("player_a3", res[2], isActive));
                    daddy.appendChild(inputHelper("player_b1", res[3], isActive));
                    daddy.appendChild(inputHelper("player_b2", res[4], isActive));
                    daddy.appendChild(inputHelper("player_b3", res[5], isActive));
                }
        daddy = document.getElementById("tablebutton");
        daddy.innerHTML = '';
        b = document.createElement("button");
        if (isActive){
            b.type = "submit";
            b.innerHTML = "Neui Sitzornig bestätige";
            b.addEventListener("click", function(){
                changePlayerNames(noOfPlayers, res);
                buildTable(noOfPlayers, res, false);
                });
            b2 = document.createElement("button");
            b2.type = "reset";
            b2.innerHTML = "Abbräche";
            b2.addEventListener("click", function(){
                buildTable(noOfPlayers, res, false);
                });
            daddy.appendChild(b2);
        }
        if (!isActive){
            b.type = "reset";
            b.innerHTML = "Sitzornig ändere";
            b.addEventListener("click", function(){
                buildTable(noOfPlayers, res, true);
                });
        }
        daddy.appendChild(b);
    }

    function inputHelper(c, placeholder, isActive){
        i = document.createElement("input");
        i.className = c;
        i.placeholder = placeholder;
        if (!isActive){
            i.disabled = true;
        }
        return i;

    }
    function changePlayerNames(noOfPlayers, players){
        if (noOfPlayers == 4){
            classes = ["player_a1", "player_a2_4", "player_b1_4", "player_b2_4"]
        }
        if (noOfPlayers == 6){
            classes = ["player_a1", "player_a2", "player_a3", "player_b1", "player_b2", "player_b3"]
        }
        for (const [index, v] of classes.entries()) {
            i = document.getElementsByClassName(v)[0];
            if (i.value != ""){
                players[index] = i.value;
            }
        }
        if (noOfPlayers == 4){
            teamA = players.slice(0, 2);
            teamB = players.slice(2);
        }
        if (noOfPlayers == 6){
            teamA =  players.slice(0, 3);
            teamB = players.slice(3);
        }
        a = teamA.join(';');
        b = teamB.join(';');
       fetch('php/changePlayerNames.php', {
            method: 'POST',
            headers: {
                "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8",
            },
            body: `teamA=${a}&teamB=${b}`,
        })         
    }
    function changeTeamNames(names){
        inputs = document.getElementsByClassName('teamName');
        if (inputs[0].value != ""){
                names[0] = inputs[0].value;
        }
        if (inputs[1].value != ""){
            names[1] = inputs[1].value;
        }
        fetch('php/changeTeamNames.php', {
            method: 'POST',
            headers: {
                "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8",
            },
            body: `teamA=${names[0]}&teamB=${names[1]}`,
        });
    }
    function loadSettings(groupName, isActive){
        fetch('php/isTournament.php', {
            method: 'POST',
            headers: {
                "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8",
            },
            body: `name=${groupName}`,
        }).then((response) => response.text())
            .then((res) => buildSettings(groupName, res, isActive));
    }
    function buildSettings(groupName, isTournament, isActive){
        fetch('php/getSettings.php', {
            method: 'POST',
            headers: {
                "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8",
            },
            body: `name=${groupName}`,
        }).then((response) => response.json())
            .then((res) => buildSettingsHelper(isTournament, res, isActive));
        
    }
    function buildSettingsHelper(isTournament, res, isActive){
        turniersieg = res[0];
        matsch = res[1];
        kontermatsch = res[2];
        sieg = res[3];
        cash = res[4];
        minimum = res[5];
        daddy = document.getElementById('settings');
        daddy.innerHTML = '';
        para = document.createElement('p');
        para.innerText = "Folgendi Istellige gältet für di aktivi Gruppe:"
        daddy.appendChild(para);
        ul = document.createElement('ul');
        if (isTournament == "1"){
            child = document.createElement('li');
            child.innerText = 'Modus: Turnier';
            ul.appendChild(child);
            ul.appendChild(liHelper('Punggt zum Turniersieg: ' , turniersieg, "turniersieg", isActive));
            ul.appendChild(liHelper('Punggt pro Match: ' , matsch, "matsch", isActive));
            ul.appendChild(liHelper('Punggt pro Kontermatch: ' , kontermatsch, "kontermatsch", isActive));
            ul.appendChild(liHelper('Punggt pro Sieg: ' , sieg, "sieg", isActive));
        }
        else if (isTournament == "0") {
            child = document.createElement('li');
            child.innerText = 'Modus: Cashgame';
            ul.appendChild(child);
            ul.appendChild(liHelper('Betrag pro 100 Pünggt pro Spieler in CHF: ' , cash, "cash", isActive));
            ul.appendChild(liHelper('Minimumbetrag pro Spieler in CHF: ' , minimum, "minimum", isActive));
        }
        daddy.appendChild(ul);
        b = document.createElement("button");
        if (isActive){
            b.type = "submit";
            b.innerHTML = "Neui Settings bestätige";
            b.addEventListener("click", function(){
                changeSettings(isTournament, res);
                });
            b2 = document.createElement("button");
            b2.type = "reset";
            b2.innerHTML = "Abbräche";
            b2.addEventListener("click", function(){
                buildSettingsHelper(isTournament, res, false);
                });
            daddy.appendChild(b2);
        }
        if (!isActive){
            b.type = "reset";
            b.innerHTML = "Istellige ändere";
            b.addEventListener("click", function(){
                buildSettingsHelper(isTournament, res, true);
                });
        }
        daddy.appendChild(b);
    }
    function liHelper(text, placeholder, id, isActive){
        child = document.createElement('li');
        i = document.createElement("input");
        i.classList.add('tinyInput');
        i.id = id;
        i.placeholder = placeholder;
        if (!isActive){
            i.disabled = true;
        }
        child.innerText = text;
        child.appendChild(i);
        return child;
    }
    function changeSettings(isTournament, currVals){
        newVals = currVals.slice();
        classes = ['turniersieg', 'matsch', 'kontermatsch', 'sieg', 'cash', 'minimum'];
        for (const [index, v] of classes.entries()) {
            i = document.getElementById(v);
            if (i && i.value != ""){
                newVals[index] = i.value;
            }
        }
       fetch('php/changeSettings.php', {
            method: 'POST',
            headers: {
                "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8",
            },
            body: `settings=${newVals}&old=${currVals}`,
        });
        buildSettingsHelper(isTournament, newVals, false);
    }
    loadGroupList();
    loadMid(<?php echo $_SESSION['noPlayers'] ?>, false);
    loadSettings('<?php echo getGroupName($_SESSION['activeGroup'])?>', false);
    </script>
</body>
</html>
