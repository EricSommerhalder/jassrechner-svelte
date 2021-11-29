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
    if ( !isset($_POST['benutzer'], $_POST['passwort']) ) {
        // Could not get the data that should have been sent.
        exit('Bitte beidi Fälder usfülle!');
    }
    // Prepare our SQL, preparing the SQL statement will prevent SQL injection.
    if ($stmt = $mysqli->prepare('SELECT id, passwort FROM Benutzer WHERE name = ?')) {
        // Bind parameters (s = string, i = int, b = blob, etc), in our case the username is a string so we use "s"
        $stmt->bind_param('s', $_POST['benutzer']);
        $stmt->execute();
        // Store the result so we can check if the account exists in the database.
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $stmt->bind_result($id, $passwort);
            $stmt->fetch();
            // Account exists, now we verify the password.
            // Note: remember to use password_hash in your registration file to store the hashed passwords.
            if (password_verify($_POST['passwort'], $passwort)) {
                // Verification success! User has logged-in!
                // Create sessions, so we know the user is logged in, they basically act like cookies but remember the data on the server.
                session_regenerate_id();
                $_SESSION['loggedin'] = TRUE;
                $_SESSION['name'] = $_POST['benutzer'];
                $_SESSION['id'] = $id;
                echo 'Willkomme ' . $_SESSION['name'] . '!';
            } else {
                echo 'Falsche Name/E-Mail oder Passwort';
            }
        } else {
            //Try with e-mail
            if ($stmt2 = $mysqli->prepare('SELECT id, name, passwort FROM Benutzer WHERE email = ?')) {
                // Bind parameters (s = string, i = int, b = blob, etc), in our case the username is a string so we use "s"
                $stmt2->bind_param('s', $_POST['benutzer']);
                $stmt2->execute();
                // Store the result so we can check if the account exists in the database.
                $stmt2->store_result();
                if ($stmt2->num_rows > 0) {
                    $stmt2->bind_result($id, $name, $passwort);
                    $stmt2->fetch();
                    // Account exists, now we verify the password.
                    // Note: remember to use password_hash in your registration file to store the hashed passwords.
                    if (password_verify($_POST['passwort'], $passwort)) {
                        // Verification success! User has logged-in!
                        // Create sessions, so we know the user is logged in, they basically act like cookies but remember the data on the server.
                        session_regenerate_id();
                        $_SESSION['loggedin'] = TRUE;
                        $_SESSION['name'] = $name;
                        $_SESSION['id'] = $id;
                        echo 'Willkomme ' . $_SESSION['name'] . '!';
                    } else {
                        // Incorrect password
                        echo 'Falsche Name/E-Mail oder Passwort';
                    }
                } else {
                    // Incorrect username
                    echo 'Falsche Name/E-Mail oder Passwort';
                }
        
                $stmt2->close();
            }  
        }

        $stmt->close();
    }
?>