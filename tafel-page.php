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
    function focusOut(id) {
        var x = document.getElementById(id);
        document.getElementById(id.charAt(0) + '0').style.backgroundColor = "";
        document.getElementById('Diszi' + id.substring(1)).style.backgroundColor = "";
        let val = x.value.trim();
        if (val == '') {
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
        <aside class="leftside"></aside>
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
                        <td><input id="A1" onfocusout="focusOut('A1')" onfocus="highlight('A1')" /></td>
                        <td><input id="B1" onfocusout="focusOut('B1')" onfocus="highlight('B1')" /></td>
                    </tr>
                    <tr>
                        <td id="Diszi2">Chrüüz</td>
                        <td><input id="A2" onfocusout="focusOut('A2')" onfocus="highlight('A2')" /></td>
                        <td><input id="B2" onfocusout="focusOut('B2')" onfocus="highlight('B2')" /></td>
                    </tr>
                    <tr>
                        <td id="Diszi3">Egge</td>
                        <td><input id="A3" onfocusout="focusOut('A3')" onfocus="highlight('A3')" /></td>
                        <td><input id="B3" onfocusout="focusOut('B3')" onfocus="highlight('B3')" /></td>
                    </tr>
                    <tr>
                        <td id="Diszi4">Härz</td>
                        <td><input id="A4" onfocusout="focusOut('A4')" onfocus="highlight('A4')" /></td>
                        <td><input id="B4" onfocusout="focusOut('B4')" onfocus="highlight('B4')" /></td>
                    </tr>
                    <tr>
                        <td id="Diszi5">Misere</td>
                        <td><input id="A5" onfocusout="focusOut('A5')" onfocus="highlight('A5')" /></td>
                        <td><input id="B5" onfocusout="focusOut('B5')" onfocus="highlight('B5')" /></td>
                    </tr>
                    <tr>
                        <td id="Diszi6">Unde</td>
                        <td><input id="A6" onfocusout="focusOut('A6')" onfocus="highlight('A6')" /></td>
                        <td><input id="B6" onfocusout="focusOut('B6')" onfocus="highlight('B6')" /></td>
                    </tr>
                    <tr>
                        <td id="Diszi7">Obe</td>
                        <td><input id="A7" onfocusout="focusOut('A7')" onfocus="highlight('A7')" /></td>
                        <td><input id="B7" onfocusout="focusOut('B7')" onfocus="highlight('B7')" /></td>
                    </tr>
                    <tr>
                        <td id="Diszi8">Slalom</td>
                        <td><input id="A8" onfocusout="focusOut('A8')" onfocus="highlight('A8')" /></td>
                        <td><input id="B8" onfocusout="focusOut('B8')" onfocus="highlight('B8')" /></td>
                    </tr>
                    <tr>
                        <td id="Diszi9"><?php if ($_SESSION['noPlayers'] == 6){ echo '2-2-2' ;} elseif ($_SESSION['noPlayers'] == 4){ echo '3-3-3' ;} else {echo 'kei Ahnig';}?></td>
                        <td><input id="A9" onfocusout="focusOut('A9')" onfocus="highlight('A9')" /></td>
                        <td><input id="B9" onfocusout="focusOut('B9')" onfocus="highlight('B9')" /></td>
                    </tr>
                    <tr>
                        <td id="Diszi10">Frei</td>
                        <td><input id="A10" onfocusout="focusOut('A10')" onfocus="highlight('A10')" /></td>
                        <td><input id="B10" onfocusout="focusOut('B10')" onfocus="highlight('B10')" /></td>
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
            <!--<div class="infobox_turnier">
                <table>
                <tr>
                    <td colspan="3">Zwischenstand Turnier</td>      
                </tr>
                <tr>
                    <td></td>
                    <td>Team 1</td>
                    <td>Team 2</td>
                </tr>
                <tr>
                    <td>Punkte</td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td colspan="3">Zwischenstand Spiel</td>
                </tr>
                <tr>
                    <td>Matchpunkte</td>
                    <td></td>
                    <td></td>
                </tr>
                </table>
            </div>-->
            <div class="infobox_cashgame">
                <p>Team 1 müend<br>5 Stutz<br>pro Spiiler zahle</p>

                </table>
            </div>
        </aside>
    </div>
    <footer>
    </footer>

</body>
<script>
    initValueFields();
    getTotal();
    function loadAusgeber(name){
        daddy = document.getElementById("ausgeber");
        daddy.innerHTML = "Uusgäh dörf:<br> " + name;
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
</script>

</html>
