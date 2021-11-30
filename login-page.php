<!DOCTYPE html>
<html lang="de">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jassrechner | Login</title>
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/styles.css ">
  </head>
  <body>
    <div class="content">
      <aside class="leftside"></aside>
      <main>
        <h2>Grüezi bim Jassrächner</h2>
        <p>Bitte amälde!</p>
        <form action="php/login.php" method="post">
          <input class="inputbox" type="text" placeholder="Benutzer" name="benutzer" required>
          <input class="inputbox"type="password" placeholder="Passwort" name="passwort" required>
          <button class="submitbtn" type="submit">AMÄLDE</button>
        </form>
      </main>
      <aside class="rightside"></aside>
    </div>
    <footer>
    </footer>

  </body>
</html>