<?php
//Niklas Steigmiller
//Sessionnstart und Einbindung der benötigten Dateien
session_start();

require_once "../Verbindung.php";
require_once "Rennveranstalter.php";
//Instanziierung der Rennveranstalterklasse & Initialisierung der Variablen
$rv = new Rennveranstalter();

$fehlerrv = "";
$VName    = "";
$NName    = "";
$Kennwort = "";
//Nur ausführen, wenn das Formular abgeschickt wurde

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //Eingabewerte aus dem Formular holen und trimmen
    $VName    = trim($_POST['vorname']);
    $NName    = trim($_POST['nachname']);
    $Kennwort = trim($_POST['kennwort']);
    //Eingabevalidierung
    if (empty($VName) || empty($NName) || empty($Kennwort)) {
        $fehlerrv = "Alle Felder müssen ausgefüllt werden.";
    } else { //Login-Versuch mit der Rennveranstalterklasse
        if ($rv->login($VName, $NName, $Kennwort)) {
            //Login erfolgreich, Session-Variablen setzen und zum Dashboard weiterleiten
            $_SESSION['vname'] = $VName;
            $_SESSION['nname'] = $NName;
            header("Location: rennveranstalter_dashboard.php");
            exit();
        } else { //Login fehlgeschlagen, Fehlermeldung setzen und Kennwort zurücksetzen
            $fehlerrv = "Ungültige Anmeldedaten.";
            $Kennwort = "";
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Anmelden</title>
</head>
<body>

    <?php if (!empty($fehlerrv)): ?> //Fehlermeldung anzeigen, wenn vorhanden
        <p style="color:red;">
            <?php echo htmlspecialchars($fehlerrv, ENT_QUOTES, 'UTF-8'); ?> //Sicherheitsmaßnahme gegen XSS
        </p>
    <?php endif; ?>

    <h2>Als Rennveranstalter anmelden</h2>
    <!--Formular für Rennveranstalter-Login-->
    <form method="post" action=""> 
        <label>Vorname:</label><br>
        <input type="text" name="vorname"
               value="<?php echo htmlspecialchars($VName, ENT_QUOTES, 'UTF-8'); ?>"
               required><br><br>

        <label>Nachname:</label><br>
        <input type="text" name="nachname"
               value="<?php echo htmlspecialchars($NName, ENT_QUOTES, 'UTF-8'); ?>"
               required><br><br>

        <label>Kennwort:</label><br>
        <input type="password" name="kennwort" required><br><br>

        <button type="submit">Anmelden</button>
    </form>
    <a href="rennveranstalter_registrieren.php">Neu registrieren</a>
    <!--Link zur Registrierungsseite-->

    <!--Anton Nguyen-->
    <!-- Formular für Teamchef-Login  sendet direkt an das Teamchef-Dashboard -->

    <hr>

    <h2>Als Teamchef anmelden</h2>
    <form method="post" action="../anton/dashboard.php">
        <label>Loginname:</label><br>
        <input type="text" name="loginname" required><br><br>

        <label>Kennwort:</label><br>
        <input type="password" name="kennwort" required><br><br>

        <button type="submit">Anmelden</button>
    </form>
    <br>
    <a href="../TeamAnlegen.php"> Team anlegen</a>

</body>
</html>