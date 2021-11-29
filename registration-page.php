<!DOCTYPE html>
<html lang="de">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" con tent="width=device-width, initial-scale=1.0">
    <title>Jassrechner | Registrieren</title>
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/styles.css ">
  </head>
  <body>
    <div class="content">
      <aside class="leftside"></aside>
      <main>
        <h2>Grüezi bim Jassrächner</h2>
        <p>Jetz registriere!</p>
        <form action="php/register.php" method="post">
          <input class="inputbox" type="text" placeholder="Username" name="name" required>
          <input class="inputbox" type="email" placeholder="Email" name="email" required>       
          <input class="inputbox"type="password" placeholder="Passwort" name="passwort" required>
          <input class="inputbox"type="password" placeholder="Passwort wiederholen" name="confirm" required>
          <input class="submitbtn" type="submit" value="REGISTRIEREN">
        </form>
      </main>
      <aside class="rightside"></aside>
    </div>
    <footer>
    </footer>

  </body>
</html>