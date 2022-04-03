<?php
    session_start();
    require('util.php');
   
    $mysqli = setup();
    if ( !isset($_POST['benutzer'], $_POST['passwort']) ) {
        // Could not get the data that should have been sent.
        exit('Bitte beidi Fälder usfülle!');
    }
    // Prepare our SQL, preparing the SQL statement will prevent SQL injection.
    $stmt = getUserByName($_POST['benutzer']);
        if ($stmt->num_rows > 0) {
            $stmt->bind_result($id, $passwort, $e, $g);
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
                $_SESSION['msgError'] = '';
                header('Location: ../group-page.php');
            } else {
                $_SESSION['msgError'] = 'Falsche Name/E-Mail oder Passwort 1';
                header('Location: ../login-page.php');
            }
        } else {
            //Try with e-mail
            $stmt2 = getUserByEmail($_POST['benutzer']);
                if ($stmt2->num_rows > 0) {
                    $stmt2->bind_result($id, $name, $passwort, $g);
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
                        header('Location: ../group-page.php');
                    } else {
                        // Incorrect password
                        $_SESSION['msgError'] = 'Falsche Name/E-Mail oder Passwort 2';
                        header('Location: ../login-page.php');
                    }
                } else {
                    // Incorrect username
                    $_SESSION['msgError'] = 'Falsche Name/E-Mail oder Passwort 3';
                    header('Location: ../login-page.php');
                }
        
                $stmt2->close();
              
        }

        $stmt->close();
    $mysqli->close();
        
?>