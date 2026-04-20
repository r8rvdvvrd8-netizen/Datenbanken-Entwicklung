<!DOCTYPE html>
<html>
    <head>
        <title>Team Anlegen</title>
    </head>
    <body>
    
        <h2>Team anlegen</h2>

<p><?php echo $message; ?></p>

<form method="POST">
    <label>Teamname:</label><br>
    <input type="text" name="teamname" required><br><br>

    <label>Vorname Teamchef:</label><br>
    <input type="text" name="vorname" required><br><br>

    <label>Nachname Teamchef:</label><br>
    <input type="text" name="nachname" required><br><br>

    <label>Loginname:</label><br>
    <input type="text" name="loginname" required><br><br>

    <label>Passwort:</label><br>
    <input type="password" name="passwort" required><br><br>

    <button type="submit">Team anlegen</button>
</form>
        <br>
        <a href="../index.php">Zurück zur Startseite</a>
    </body>
</html>

