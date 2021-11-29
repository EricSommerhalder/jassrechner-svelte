<?php
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

  if (!isset($_POST['name'], $_POST['passwort'], $_POST['email'], $_POST['confirm'])) {
    // Could not get the data that should have been sent.
    exit('Bitte s ganze Formular usfülle');
  }
  if (empty($_POST['name']) || empty($_POST['passwort']) || empty($_POST['email']) || empty($_POST['confirm'])) {
    // One or more values are empty.
    exit('Bitte s ganze Formular usfülle');
  }
  if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
    exit('Dasch aber kei E-Mail-Adrässe du!');
  }  
  if (strlen($_POST['passwort']) > 20 || strlen($_POST['passwort']) < 5) {
    exit('S Passwort miessti denn zwüsche 5 und 19 Zeiche lang si jä?');
  }

  if ($_POST['passwort'] != $_POST['confirm']){
    exit('Passwörter sind nid gliich!');
  }
  $name = $_POST['name'];
  $email = $_POST['email'];
  $passwort = password_hash($_POST['passwort'], PASSWORD_DEFAULT);
  
  if ($stmt = $mysqli->prepare('SELECT id, passwort FROM Benutzer WHERE name = ?')) {
    // Bind parameters (s = string, i = int, b = blob, etc), hash the password using the PHP password_hash function.
    $stmt->bind_param('s', $name);
    $stmt->execute();
    $stmt->store_result();
    // Store the result so we can check if the account exists in the database.
    if ($stmt->num_rows > 0) {
      // Username already exists
      echo 'Name existiert scho!';
    } else {
      if ($stmt2 = $mysqli->prepare('SELECT id, passwort FROM Benutzer WHERE email = ?')) {
        // Bind parameters (s = string, i = int, b = blob, etc), hash the password using the PHP password_hash function.
        $stmt2->bind_param('s', $email);
        $stmt2->execute();
        $stmt2->store_result();
        // Store the result so we can check if the account exists in the database.
        if ($stmt2->num_rows > 0) {
          // Username already exists
          echo 'Email existiert scho!';
        } else {
          // Insert new account
          $sql = "INSERT INTO Benutzer (name, email, passwort) VALUES ('$name', '$email', '$passwort')";
          if ($mysqli->query($sql) === TRUE) {
            echo "Het tiptop funktioniert";
          } else {
            echo "Fähler: " . $sql . "<br>" . $mysqli->error;
          }
        }
        $stmt2->close();
      } else {
        // Something is wrong with the sql statement, check to make sure accounts table exists with all 3 fields.
        echo 'Öppis het nid funktioniert, bitte nomol probiere';
      }
    }
    $stmt->close();
  } else {
    // Something is wrong with the sql statement, check to make sure accounts table exists with all 3 fields.
    echo 'Öppis het nid funktioniert, bitte nomol probiere';
  }
  
  $mysqli->close();
?>