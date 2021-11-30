<?php
  require('util.php');
   
  $mysqli = setup();

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
  if (preg_match('/^[a-zA-Z0-9]+$/', $_POST['name']) == 0) {
    exit('Ungültigi Zeiche im Benutzername vewändet!');
}

  if ($_POST['passwort'] != $_POST['confirm']){
    exit('Passwörter sind nid gliich!');
  }
  $name = $_POST['name'];
  $email = $_POST['email'];
  $passwort = password_hash($_POST['passwort'], PASSWORD_DEFAULT);
  
  $stmt = getUserByName($name); 
    // Store the result so we can check if the account exists in the database.
    if ($stmt->num_rows > 0) {
      // Username already exists
      echo 'Name existiert scho!';
    } else {
      $stmt2 = getUserByEmail($email);
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
        $stmt2->close();
    }
    $stmt->close();
  } 
  
  $mysqli->close();
?>