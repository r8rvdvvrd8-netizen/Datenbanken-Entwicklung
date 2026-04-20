<!DOCTYPE html>
<html>
    <head>
        <title>Anmelden</title>
    </head>
    <body>
        <a href="../index.php">Zurück zur Startseite</a>
        <h1>Anmelden</h1>

        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_once '../Verbindung.php';

            $loginname = mysqli_real_escape_string($connection, $_POST['loginname']);
            $kennwort  = $_POST['kennwort'];

            $result = mysqli_query($connection, "SELECT * FROM teamchef WHERE loginname = '$loginname'");
            $user   = mysqli_fetch_assoc($result);

            if ($user && password_verify($kennwort, $user['kennwort'])) {
                echo "<p>Anmeldung erfolgreich. Willkommen, " . htmlspecialchars($user['vorname']) . "!</p>";
            } else {
                echo "<p style='color:red;'>Loginname oder Kennwort falsch.</p>";
            }
        }
        ?>

        <form method="post" action="">
            <label>Loginname:</label><br>
            <input type="text" name="loginname" required><br><br>
            <label>Kennwort:</label><br>
            <input type="password" name="kennwort" required><br><br>
            <button type="submit">Anmelden</button>
        </form>
    </body>
</html>
