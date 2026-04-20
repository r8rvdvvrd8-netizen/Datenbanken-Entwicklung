<!DOCTYPE html>
<html>
    <head>
        <title>Team anlegen</title>
    </head>
    <body>
        <a href="../index.php">Zurück zur Startseite</a>
        <h1>Team anlegen</h1>

        <?php
        require_once '../Verbindung.php';

        // Tabellen anlegen falls nicht vorhanden
        mysqli_query($connection, "
            CREATE TABLE IF NOT EXISTS team (
                id INT AUTO_INCREMENT PRIMARY KEY,
                teamname VARCHAR(100) NOT NULL UNIQUE
            )
        ");
        mysqli_query($connection, "
            CREATE TABLE IF NOT EXISTS teamchef (
                id INT AUTO_INCREMENT PRIMARY KEY,
                vorname VARCHAR(100) NOT NULL,
                name VARCHAR(100) NOT NULL,
                loginname VARCHAR(100) NOT NULL UNIQUE,
                kennwort VARCHAR(255) NOT NULL,
                team_id INT NOT NULL,
                FOREIGN KEY (team_id) REFERENCES team(id)
            )
        ");

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $teamname  = mysqli_real_escape_string($connection, trim($_POST['teamname']));
            $vorname   = mysqli_real_escape_string($connection, trim($_POST['vorname']));
            $name      = mysqli_real_escape_string($connection, trim($_POST['name']));
            $loginname = mysqli_real_escape_string($connection, trim($_POST['loginname']));
            $kennwort  = password_hash($_POST['kennwort'], PASSWORD_DEFAULT);

            // Prüfen ob Teamname schon existiert
            $check = mysqli_query($connection, "SELECT id FROM team WHERE teamname = '$teamname'");
            if (mysqli_num_rows($check) > 0) {
                echo "<p style='color:red;'>Dieser Teamname ist bereits vergeben.</p>";
            } else {
                // Team einfügen
                mysqli_query($connection, "INSERT INTO team (teamname) VALUES ('$teamname')");
                $team_id = mysqli_insert_id($connection);

                // Teamchef einfügen
                $insert = mysqli_query($connection, "
                    INSERT INTO teamchef (vorname, name, loginname, kennwort, team_id)
                    VALUES ('$vorname', '$name', '$loginname', '$kennwort', $team_id)
                ");

                if ($insert) {
                    echo "<p style='color:green;'>Team <strong>" . htmlspecialchars($teamname) . "</strong> wurde erfolgreich angelegt.</p>";
                } else {
                    // Loginname schon vergeben — Team-Eintrag wieder löschen
                    mysqli_query($connection, "DELETE FROM team WHERE id = $team_id");
                    echo "<p style='color:red;'>Dieser Loginname ist bereits vergeben.</p>";
                }
            }
        }
        ?>

        <form method="post" action="">
            <label>Teamname:</label><br>
            <input type="text" name="teamname" required><br><br>

            <label>Vorname Teamchef:</label><br>
            <input type="text" name="vorname" required><br><br>

            <label>Name Teamchef:</label><br>
            <input type="text" name="name" required><br><br>

            <label>Loginname:</label><br>
            <input type="text" name="loginname" required><br><br>

            <label>Kennwort:</label><br>
            <input type="password" name="kennwort" required><br><br>

            <button type="submit">Team anlegen</button>
        </form>
    </body>
</html>
