<?php

// We need to use sessions, so you should always start sessions using the below code.
session_start();
require('php/util.php');
// If the user is not logged in redirect to the login page...
if (!isset($_SESSION['id'])) {
	header('Location: login-page.php');
	exit;
}

$group = getActiveGroup($_SESSION['id']);
if ($group == NULL){
  header('Location: group-page1.php');
  exit;
} 

  $game = getActiveGame($_SESSION['activeGroup']);
  if ($game == NULL){
    header('Location: group-page2.php');
    exit;
  }
  getAusgeber();
  /*
  if (!isset($_SESSION['ausgeber']) || $_SESSION['ausgeber'] == NULL){
    header('Location: group-page3.php');
    exit;
  }*/
  getNoPlayers();
  if (!isset($_SESSION['noPlayers']) || $_SESSION['noPlayers'] == NULL){
    header('Location: group-page4.php');
    exit;
  }
  getPlayerNames();
  if (!isset($_SESSION['players']) || $_SESSION['players'] == NULL){
    header('Location: group-page5.php');
    exit;
  }
  getTeamNames();
  if (!isset($_SESSION['teamnames']) || $_SESSION['teamnames'] == NULL){
    header('Location: group-page6.php');
    exit;
  }
  getTafel();
  if (!isset($_SESSION['tafel']) || $_SESSION['tafel'] == NULL){
    header('Location: group-page7.php');
    exit;
  }

?>
<!DOCTYPE html>
<script>
    let deleteState = false;
    function deleteClicked(){
        deleteState = !deleteState;
        if (deleteState){
            deleteStateReached();
        }
        if (!deleteState){
            deleteStateLeft();
        }
    }
    function deleteStateLeft(){
        button = document.getElementById("deleteButton");
        button.innerText = "Iitrag lösche";
        color = "";
        let chars = ['A', 'B'];
        for (let i = 1; i <= 10; i++){
            for (let a of chars){
                id  = a + i.toString();
                grandPa = document.getElementById("cell" + id);
                evilTwin = document.getElementById("s" + id);
                child = document.createElement("input");
                child.id = id;
                child.setAttribute('onfocusout', "focusOut('" + id + "')");
                child.setAttribute('onfocus', "highlight('" + id + "')");
                child.style.backgroundColor = color;
                child.value = evilTwin.innerText;
                if (child.value == ""){
                    child.disabled = false;
                } else {
                    child.disabled = true;
                }
            child.addEventListener("keyup", function(event) {
                if (event.keyCode === 13) {
                    event.preventDefault();
                    focusOut(id);
                }
            });
                grandPa.appendChild(child);
                grandPa.removeChild(evilTwin);
            }
        }
    }
    function deleteStateReached(){
        button = document.getElementById("deleteButton");
        button.innerText = "Löschmodus vrlo";
        color = "#800000";
        let chars = ['A', 'B'];
        for (let i = 1; i <= 10; i++){
            for (let a of chars){
                id  = a + i.toString();
                grandPa = document.getElementById("cell" + id);
                daddy = document.getElementById(id);
                daddy.disabled = true;
                child = document.createElement("span");
                child.setAttribute("onclick", 'deleteField("' + id + '")');
                child.innerText = daddy.value;
                if (daddy.value != "") {
                    child.style.backgroundColor = "#800000";
                }
                child.style.display = "block";
                child.id = "s" + id;
                grandPa.appendChild(child);
                grandPa.removeChild(daddy);
            }
        }
    }
    function deleteField(id){
        s = document.getElementById("s" + id);
        s.innerText = "";
        fetch('php/deleteTafelValue.php', {
            method: 'POST',
            headers: {
                "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8",
            },
            body: `id=${id}`,
        }).then((response) => response.text()).then((res) => loadAusgeber(res));
        deleteClicked();
        getTotal();
        loadInfo();
        checkFinished(); 
    }
    function focusOut(id) {
        var x = document.getElementById(id);
        document.getElementById(id.charAt(0) + '0').style.backgroundColor = "";
        document.getElementById('Diszi' + id.substring(1)).style.backgroundColor = "";
        let val = x.value.trim();
        if (val == ''){
            return;
        }
        if (isNaN(val)) {
            if (val.indexOf(' | ') != -1){
                return;
            }
            x.value = '';
            return;
        }
        val = parseInt(val);
        if (val < 0 || (val > 157 && val != 257) || val == 156){
            x.value = '';
            return;
        }
        fetch('php/setTafelValue.php', {
            method: 'POST',
            headers: {
                "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8",
            },
            body: `id=${id}&value=${val}`,
        }).then((response) => response.text()).then((res) => loadAusgeber(res));
        x.disabled = true;
        x.value = getDisziString(parseInt(id.substring(1)), val);
        getTotal();
        loadInfo();
        checkFinished(); 
    }

    function getDisziString(multiplicator, value){
        if (value == -1){
            return "";
        }
        return value.toString() + ' | ' + (multiplicator * value).toString();
    }

    function initValueFields(){
        let chars = ['A', 'B'];
        for (let i = 1; i <= 10; i++){
            for (let a of chars){
                index = i -1;
                document.getElementById(a + i.toString()).value = getDisziString(i, getFieldVal(a + i.toString()))
            }
        }
    }
    function getFieldVal(id){
        if (id == "A1"){
            return <?php echo $_SESSION['tafel'][0]?>
        }
        if (id == "A2"){
            return <?php echo $_SESSION['tafel'][1]?>
        }
        if (id == "A3"){
            return <?php echo $_SESSION['tafel'][2]?>
        }
        if (id == "A4"){
            return <?php echo $_SESSION['tafel'][3]?>
        }
        if (id == "A5"){
            return <?php echo $_SESSION['tafel'][4]?>
        }
        if (id == "A6"){
            return <?php echo $_SESSION['tafel'][5]?>
        }
        if (id == "A7"){
            return <?php echo $_SESSION['tafel'][6]?>
        }
        if (id == "A8"){
            return <?php echo $_SESSION['tafel'][7]?>
        }
        if (id == "A9"){
            return <?php echo $_SESSION['tafel'][8]?>
        }
        if (id == "A10"){
            return <?php echo $_SESSION['tafel'][9]?>
        }
        if (id == "B1"){
            return <?php echo $_SESSION['tafel'][10]?>
        }
        if (id == "B2"){
            return <?php echo $_SESSION['tafel'][11]?>
        }
        if (id == "B3"){
            return <?php echo $_SESSION['tafel'][12]?>
        }
        if (id == "B4"){
            return <?php echo $_SESSION['tafel'][13]?>
        }
        if (id == "B5"){
            return <?php echo $_SESSION['tafel'][14]?>
        }
        if (id == "B6"){
            return <?php echo $_SESSION['tafel'][15]?>
        }
        if (id == "B7"){
            return <?php echo $_SESSION['tafel'][16]?>
        }
        if (id == "B8"){
            return <?php echo $_SESSION['tafel'][17]?>
        }
        if (id == "B9"){
            return <?php echo $_SESSION['tafel'][18]?>
        }
        if (id == "B10"){
            return <?php echo $_SESSION['tafel'][19]?>
        }
        return NAN;
    }

    function getTotal(){
        let totalA = 0;
        let totalB = 0;
        for (let i = 1; i <= 10; i++){
            val = document.getElementById("A" + i.toString()).value;
            if (val != ""){
                totalA += parseInt(val.substring(val.indexOf("|") + 2))
            }
            val = document.getElementById("B" + i.toString()).value;
            if (val != ""){
                totalB += parseInt(val.substring(val.indexOf("|") + 2))
            }
        }
        document.getElementById('totalA').value = totalA;
        document.getElementById('totalB').value = totalB;
        if (totalA > totalB){
            document.getElementById('vorsprungA').value = totalA - totalB;
            document.getElementById('vorsprungB').value = "";
        }
        if (totalB > totalA){
            document.getElementById('vorsprungB').value = totalB - totalA;
            document.getElementById('vorsprungA').value = "";
        }
        if (totalA == totalB){
            document.getElementById('vorsprungA').value = "";
            document.getElementById('vorsprungB').value = "";
        }
    }
    function highlight(id) {
        document.getElementById(id.charAt(0) + '0').style.backgroundColor = "#4b6c64";
        document.getElementById('Diszi' + id.substring(1)).style.backgroundColor = "#4b6c64";
    }

</script>
<html lang="de">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jassrechner</title>
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/styles.css ">
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
      <p class="crumb">Aktivi Gruppe: <?php echo getGroupName($_SESSION['activeGroup'])?></p>
    </nav>
    <div class="content">
        <aside class="leftside">
            <button id= "deleteButton" type="submit" onclick="deleteClicked()">Iitrag lösche</button>
            <div id="finishedButton"></div>
            <div id="tourFinishedButton"></div>
        </aside>
        <main>
            <div class="table">
                <table>
                    <tr>
                        <td></td>
                        <td id="A0"><?php echo $_SESSION['teamnames'][0]?></td>
                        <td id="B0"><?php echo $_SESSION['teamnames'][1]?></td>
                    </tr>
                    <tr>
                        <td id="Diszi1">Schuufle</td>
                        <td id = "cellA1"><input id="A1" onfocusout="focusOut('A1')" onfocus="highlight('A1')" /></td>
                        <td id = "cellB1"><input id="B1" onfocusout="focusOut('B1')" onfocus="highlight('B1')" /></td>
                    </tr>
                    <tr>
                        <td id="Diszi2">Chrüüz</td>
                        <td id = "cellA2"><input id="A2" onfocusout="focusOut('A2')" onfocus="highlight('A2')" /></td>
                        <td id = "cellB2"><input id="B2" onfocusout="focusOut('B2')" onfocus="highlight('B2')" /></td>
                    </tr>
                    <tr>
                        <td id="Diszi3">Egge</td>
                        <td id = "cellA3"><input class ="res" id="A3" onfocusout="focusOut('A3')" onfocus="highlight('A3')" /></td>
                        <td id = "cellB3"><input id="B3" onfocusout="focusOut('B3')" onfocus="highlight('B3')" /></td>
                    </tr>
                    <tr>
                        <td id="Diszi4">Härz</td>
                        <td id = "cellA4"><input id="A4" onfocusout="focusOut('A4')" onfocus="highlight('A4')" /></td>
                        <td id = "cellB4"><input id="B4" onfocusout="focusOut('B4')" onfocus="highlight('B4')" /></td>
                    </tr>
                    <tr>
                        <td id="Diszi5">Misere</td>
                        <td id = "cellA5"><input id="A5" onfocusout="focusOut('A5')" onfocus="highlight('A5')" /></td>
                        <td id = "cellB5"><input id="B5" onfocusout="focusOut('B5')" onfocus="highlight('B5')" /></td>
                    </tr>
                    <tr>
                        <td id="Diszi6">Unde</td>
                        <td id = "cellA6"><input id="A6" onfocusout="focusOut('A6')" onfocus="highlight('A6')" /></td>
                        <td id = "cellB6"><input id="B6" onfocusout="focusOut('B6')" onfocus="highlight('B6')" /></td>
                    </tr>
                    <tr>
                        <td id="Diszi7">Obe</td>
                        <td id = "cellA7"><input id="A7" onfocusout="focusOut('A7')" onfocus="highlight('A7')" /></td>
                        <td id = "cellB7"><input id="B7" onfocusout="focusOut('B7')" onfocus="highlight('B7')" /></td>
                    </tr>
                    <tr>
                        <td id="Diszi8">Slalom</td>
                        <td id = "cellA8"><input id="A8" onfocusout="focusOut('A8')" onfocus="highlight('A8')" /></td>
                        <td id = "cellB8"><input id="B8" onfocusout="focusOut('B8')" onfocus="highlight('B8')" /></td>
                    </tr>
                    <tr>
                        <td id="Diszi9"><?php if ($_SESSION['noPlayers'] == 6){ echo '2-2-2' ;} elseif ($_SESSION['noPlayers'] == 4){ echo '3-3-3' ;} else {echo 'kei Ahnig';}?></td>
                        <td id = "cellA9"><input id="A9" onfocusout="focusOut('A9')" onfocus="highlight('A9')" /></td>
                        <td id = "cellB9"><input id="B9" onfocusout="focusOut('B9')" onfocus="highlight('B9')" /></td>
                    </tr>
                    <tr>
                        <td id="Diszi10">Frei</td>
                        <td id = "cellA10"><input id="A10" onfocusout="focusOut('A10')" onfocus="highlight('A10')" /></td>
                        <td id = "cellB10"><input id="B10" onfocusout="focusOut('B10')" onfocus="highlight('B10')" /></td>
                    </tr>
                    <tr>
                        <td>Total</td>
                        <td><input id="totalA" disabled></input></td>
                        <td><input id="totalB" disabled></input></td>
                    </tr>
                    <tr>
                        <td>Vorsprung</td>
                        <td><input id="vorsprungA" disabled></input></td>
                        <td><input id="vorsprungB" disabled></input></td>
                    </tr>
                </table>
            </div>
        </main>
        <aside class="rightside">
            <div class="ausgeberBox" id="ausgeber"></div>
            <div id="infobox"></div>
        </aside>
    </div>
    <footer>
    </footer>

</body>
<script>
    function loadInfo(){
        isTournament = <?php echo isTournamentById($_SESSION['activeGroup']); ?>;
        if (isTournament){
            loadTournamentInfo();
        } else {
            loadCashInfo();
        }
    }
    function loadTournamentInfo(){
        fetch('php/getTournamentScore.php', {
            method: 'POST',
            headers: {
                "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8",
            },
            }).then((response) => response.json())
                .then((res) => loadTournamentInfoHelper(res[0], res[1], res[2], res[3], res[5]));
    }
    function loadTournamentInfoHelper(teamAPoints, teamBPoints, matsch, countermatsch, turniersieg){
        daddy = document.getElementById("infobox");
        daddy.innerHTML = "";
        div = document.createElement("div");
        div.classList.add("infobox_turnier");
        child = document.createElement("table");
        first = document.createElement("tr");
        second = document.createElement("tr");
        third = document.createElement("tr");
        fourth = document.createElement("tr");
        fifth = document.createElement("tr");

        firstfirst = document.createElement("td");
        firstfirst.colSpan = "3";
        firstfirst.innerText = "Zwüschestand Turnier";
        first.appendChild(firstfirst);
        child.appendChild(first);

        second.appendChild(document.createElement("td"));
        second.appendChild(tdHelper("<?php echo $_SESSION['teamnames'][0]?>"));
        second.appendChild(tdHelper("<?php echo $_SESSION['teamnames'][1]?>"));
        child.appendChild(second);
        
        thirdfirst = document.createElement("td")
        third.appendChild(tdHelper("Punkte"));
        third.appendChild(tdHelper(teamAPoints.toString()));
        third.appendChild(tdHelper(teamBPoints.toString()));
        child.appendChild(third);
        fourthfirst = document.createElement("td");
        fourthfirst.colSpan = "3";
        fourthfirst.innerText = "Zwüschestand Spiel";
        fourth.appendChild(fourthfirst);
        child.appendChild(fourth);
        totalA = 0;
        totalB = 0;
        for (let i = 1; i <= 10; i++){
            val = document.getElementById("A" + i.toString()).value;
            if (val.indexOf('0 | 0') != -1){
                totalB += countermatsch
            }
            if (val.indexOf('257 | ') != -1){
                totalA += matsch
            }
            val = document.getElementById("B" + i.toString()).value;
            if (val.indexOf('0 | 0') != -1){
                totalA += countermatsch
            }
            if (val.indexOf('257 | ') != -1){
                totalB += matsch
            }
        }

        fifth.appendChild(tdHelper("Matschpünggt"));
        tdA = document.createElement("td");
        tdA.id = "matschA";
        tdA.innerText = totalA.toString();
        fifth.appendChild(tdA);
        tdB = document.createElement("td");
        tdB.id = "matschB";
        tdB.innerText = totalB.toString(); 
        fifth.appendChild(tdB);
        child.appendChild(fifth);

        div.appendChild(child);
        daddy.appendChild(div);
        checkTournamentFinished(teamAPoints + totalA, teamBPoints + totalB, turniersieg);

    }
    function tdHelper(text){
        td = document.createElement("td");
        td.innerText = text;
        return td;
    }    
    function loadCashInfo(){
        daddy = document.getElementById("infobox");
        daddy.innerHTML = "";
        child = document.createElement("div");
        child.classList.add("infobox_cashgame");
        grandChild = document.createElement("p");

        let totalA = 0;
        let totalB = 0;
        for (let i = 1; i <= 10; i++){
            val = document.getElementById("A" + i.toString()).value;
            if (val != ""){
                totalA += parseInt(val.substring(val.indexOf("|") + 2))
            }
            val = document.getElementById("B" + i.toString()).value;
            if (val != ""){
                totalB += parseInt(val.substring(val.indexOf("|") + 2))
            }
        }
        if (totalA == totalB){
            grandChild.innerHTML = "Zur ziit isch usgliche und <br> niemerts muess öppis <br> zahle";
        } else {
            if (totalA > totalB){
                teamString = "<?php echo $_SESSION['teamnames'][1]?>"
                diff = totalA - totalB;
            } else {
                teamString = "<?php echo $_SESSION['teamnames'][0]?>"
                diff = totalB - totalA;
            }
            fetch('php/getCashAmount.php', {
            method: 'POST',
            headers: {
                "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8",
            },
            body: `diff=${diff}`,
            }).then((response) => response.json())
                .then((res) => {grandChild.innerHTML = teamString + " müend<br>" + res.toFixed(2) + " Stutz<br>pro Spiiler zahle";}
            );   
        }
        
        child.appendChild(grandChild);
        daddy.appendChild(child);
    }
    initValueFields();
    getTotal();
    function loadAusgeber(name){
        daddy = document.getElementById("ausgeber");
        daddy.innerHTML = "Uusgäh dörf:<br> " + name;
    }

    function checkFinished(){
        daddy = document.getElementById('finishedButton');
        daddy.innerHTML = "";
        for (const c of ['A', 'B']) {
            for (let i = 1; i < 11; i++) {
                const id = c + i.toString();
                var input = document.getElementById(id);
                if (input.value == -1 || input.value == '') {
                    return;
                }
            }
        }
        child = document.createElement('button');
        child.type = "submit";
        child.innerText = "Spiel beände & speichere";
        child.setAttribute("onclick", 'saveGame()');
        daddy.appendChild(child);
    }

    function checkTournamentFinished(teamA, teamB, turniersieg){
        daddy = document.getElementById('tourFinishedButton');
        daddy.innerHTML = "";
        if (teamA >= turniersieg || teamB >= turniersieg){
            child = document.createElement('button');
            child.type = "submit";
            child.innerText = "Fürs Turnier bedangge";
            child.setAttribute("onclick", 'saveGame()');
            daddy.appendChild(child);
        }
    }
    function saveGame(){
        fetch('php/saveGame.php', {
            method: 'POST',
            headers: {
                "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8",
            },
        }).then((response) => response.text()).then((res) => afterGame(res));
    }

    function afterGame(finished){
        console.log(finished);
        if (finished == 1){
            alert("<?php echo $_SESSION['teamnames'][0]?> het s Turnier gwunne! Gratuliere!");
        }
        if (finished == 2){
            alert("<?php echo $_SESSION['teamnames'][1]?> het s Turnier gwunne! Gratuliere!");
        }
        if (finished == 3){
            alert("S Turnier het mitme unentschiide gändet. Gratuliere!");
        }
        window.location.replace("./results-page.php");
    }
    for (const c of ['A', 'B']) {
        for (let i = 1; i < 11; i++) {
            const id = c + i.toString();
            var input = document.getElementById(id);
            if (input.value != -1 && input.value != '') {
                input.disabled = true;
            }
            input.addEventListener("keyup", function(event) {
                if (event.keyCode === 13) {
                    event.preventDefault();
                    focusOut(id);
                }
            });
        }
    }
    loadAusgeber('<?php echo $_SESSION['players'][$_SESSION['ausgeber']]?>');
    loadInfo();
    checkFinished();
</script>

</html>
