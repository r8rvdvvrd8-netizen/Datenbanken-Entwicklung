<?php
// Niklas Steigmiller
session_start();

require_once "../Verbindung.php";
require_once "Rennveranstalter.php";

$rv = new Rennveranstalter();
// Initialisierung der Variablen für Fehler- und Erfolgsmeldungen sowie die Eingabefelder. Die Werte für den Rennveranstalter werden aus der Session übernommen, um sicherzustellen, dass nur angemeldete Rennveranstalter Rennen anlegen können.
$fehler      = "";
$erfolg      = "";
$Datum       = "";
$Startort    = "";
$Kilometer   = "";
$Hoehe       = "";
$MaxSteigung = "";
$VName       = $_SESSION['vname'];
$NName       = $_SESSION['nname'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $Datum       = trim($_POST['datum']);
    $Startort    = trim($_POST['startort']);
    $Kilometer   = trim($_POST['kilometer']);
    $Hoehe       = trim($_POST['hoehe']);
    $MaxSteigung = trim($_POST['max_steigung']);

    if (empty($VName) || empty($NName) || empty($Datum) || 
        empty($Startort) || empty($Kilometer) || 
        empty($Hoehe) || empty($MaxSteigung)) {
        $fehler = "Alle Felder müssen ausgefüllt werden.";
    } else {
        //DIe Methode rennenAnlegen wird aufgerufen, um das neue Rennen in der Datenbank zu speichern. Die Eingabewerte werden übergeben, um sicherzustellen, dass alle notwendigen Informationen für das Rennen vorhanden sind. Nach erfolgreichem Anlegen des Rennens werden die Eingabefelder geleert und eine Erfolgsmeldung angezeigt.
        $rv->rennenAnlegen(
            $Datum,
            $Startort,
            $Kilometer,
            $Hoehe,
            $MaxSteigung,
            $VName,
            $NName
        );

        $erfolg      = "Rennen erfolgreich angelegt.";
        $Datum       = "";
        $Startort    = "";
        $Kilometer   = "";
        $Hoehe       = "";
        $MaxSteigung = "";
    }
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Rennen anlegen</title>
    </head>
    <body>
        <a href="rennveranstalter_dashboard.php">Zurück</a>
        <h1>Rennen anlegen</h1>

        <?php if (!empty($fehler)): ?>
            <p style="color:red;">
                <?php echo htmlspecialchars($fehler, ENT_QUOTES, 'UTF-8'); ?>
            </p>
        <?php endif; ?>

        <?php if (!empty($erfolg)): ?>
            <p style="color:green;">
                <?php echo htmlspecialchars($erfolg, ENT_QUOTES, 'UTF-8'); ?>
            </p>
        <?php endif; ?>

        <form method="post" action="">
            <label>Datum:</label><br>
            <input type="date" name="datum"
                   value="<?php echo htmlspecialchars($Datum, ENT_QUOTES, 'UTF-8'); ?>"
                   required><br><br>

            <label>Startort:</label><br>
            <input type="text" name="startort"
                   value="<?php echo htmlspecialchars($Startort, ENT_QUOTES, 'UTF-8'); ?>"
                   required><br><br>

            <label>Kilometer:</label><br>
            <input type="number" name="kilometer" step="0.01" min="0"
                   value="<?php echo htmlspecialchars($Kilometer, ENT_QUOTES, 'UTF-8'); ?>"
                   required><br><br>

            <label>Zu fahrende Höhe (m):</label><br>
            <input type="number" name="hoehe" min="0"
                   value="<?php echo htmlspecialchars($Hoehe, ENT_QUOTES, 'UTF-8'); ?>"
                   required><br><br>

            <label>Maximale Steigung (%):</label><br>
            <input type="number" name="max_steigung" step="0.01" min="0"
                   value="<?php echo htmlspecialchars($MaxSteigung, ENT_QUOTES, 'UTF-8'); ?>"
                   required><br><br>

            <button type="submit">Anlegen</button>
        </form>
    </body>
</html>