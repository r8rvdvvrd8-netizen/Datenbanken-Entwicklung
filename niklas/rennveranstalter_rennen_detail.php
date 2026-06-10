<?php
// Niklas Steigmiller
session_start();

require_once "../Verbindung.php";
require_once "Rennveranstalter.php";

$rv = new Rennveranstalter();
//Initialisierung der Variablen. RID wid aus der Session übernommen
$fehler = "";
$erfolg = "";
$rid    = $_SESSION['rid'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
// MitgliederID und Teamname aus den hidden-Feldern des Formulars auslesen
    $mid   = $_POST['mid'];
    $tname = $_POST['tname'];
// Platzierung und Fahrzeit werden nur gesetzt, wenn sie nicht leer sind. Andernfalls bleiben sie null, um anzuzeigen, dass keine Eingabe erfolgt ist.
    $platzierung = !empty(trim($_POST['platzierung']))
                   ? trim($_POST['platzierung'])
                   : null;

    $fahrzeit    = !empty(trim($_POST['fahrzeit']))
                   ? trim($_POST['fahrzeit'])
                   : null;
    // Wenn sowohl Platzierung als auch Fahrzeit null sind, wird eine Fehlermeldung gesetzt, da mindestens eines der Felder ausgefüllt sein muss. Andernfalls wird die Methode ergebnisErfassen aufgerufen, um die Ergebnisse in der Datenbank zu speichern, und eine Erfolgsmeldung wird gesetzt.
    if ($platzierung === null && $fahrzeit === null) {
        $fehler = "Mindestens ein Feld muss ausgefüllt werden!";
    } else {
        $rv->ergebnisErfassen($tname, $mid, $rid, $platzierung, $fahrzeit); // Die Methode ergebnisErfassen wird aufgerufen, um die Ergebnisse in der Datenbank zu speichern. 
        $erfolg = "Ergebnis erfolgreich gespeichert!";
    }
}
// Fahrerliste für dieses Rennen aus der Datenbank laden (nach jedem POST aktualisiert)
$fahrer = $rv->getFahrer($rid);
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Rennen Detail</title>
    </head>
    <body>
        <a href="rennveranstalter_dashboard.php">Zurück</a>
        <h1>Fahrer für das ausgewählte Rennen</h1>

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

        <?php if (empty($fahrer)): ?>
            <p>Keine Fahrer für dieses Rennen gefunden.</p>

        <?php else: ?> <!--Es wird eine Tabelle erstellt, um die Fahrerinformationen anzuzeigen.-->
            <table border="1">
                <tr>
                    <th>Startnummer</th>
                    <th>Name</th>
                    <th>Team</th>
                    <th>Platzierung</th>
                    <th>Fahrzeit</th>
                    <th>Aktion</th>
                </tr>
            
                <?php foreach ($fahrer as $f): ?>  <!--Es wird eine foreach-Schleife verwendet, um durch die Liste der Fahrer zu iterieren und die Informationen in der Tabelle anzuzeigen. Für jeden Fahrer werden die Startnummer, der Name, das Team, die Platzierung und die Fahrzeit angezeigt. Es gibt auch ein Formular, um die Platzierung und Fahrzeit zu erfassen, falls diese noch nicht vorhanden sind. Wenn die Daten bereits erfasst wurden, wird stattdessen "Bereits erfasst" angezeigt.-->
                    <tr>
                        <td>
                            <?php echo htmlspecialchars($f['startnr'], ENT_QUOTES, 'UTF-8'); ?>
                        </td>
                        <td>
                            <?php echo htmlspecialchars($f['name'], ENT_QUOTES, 'UTF-8'); ?>
                        </td>
                        <td>
                            <?php echo htmlspecialchars($f['tname'], ENT_QUOTES, 'UTF-8'); ?>
                        </td>
                        <form method="post" action="">
                            <input type="hidden" name="mid"
                                   value="<?php echo htmlspecialchars($f['mid'], ENT_QUOTES, 'UTF-8'); ?>">
                            <input type="hidden" name="tname"
                                   value="<?php echo htmlspecialchars($f['tname'], ENT_QUOTES, 'UTF-8'); ?>">
                            <td>
                                <?php if ($f['platzierung'] !== null): ?>  
                                    <?php echo htmlspecialchars($f['platzierung'], ENT_QUOTES, 'UTF-8'); ?> <!-- Wenn die Platzierung bereits erfasst wurde, wird sie angezeigt. Ansonsten wird ein Eingabefeld bereitgestellt, um die Platzierung zu erfassen.-->
                                <?php else: ?>
                                    <input type="number" name="platzierung" min="1">
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($f['fahrzeit'] !== null): ?> <!-- Wenn die Fahrzeit bereits erfasst wurde, wird sie angezeigt. Ansonsten wird ein Eingabefeld bereitgestellt, um die Fahrzeit zu erfassen.-->
                                    <?php echo htmlspecialchars($f['fahrzeit'], ENT_QUOTES, 'UTF-8'); ?>
                                <?php else: ?>
                                    <input type="time" name="fahrzeit" step="1">
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($f['platzierung'] === null || $f['fahrzeit'] === null): ?> <!-- Wenn entweder die Platzierung oder die Fahrzeit noch nicht erfasst wurde, wird ein Speichern-Button angezeigt. Ansonsten wird "Bereits erfasst" angezeigt, um anzuzeigen, dass die Daten bereits vorhanden sind.-->
                                    <button type="submit">Speichern</button>
                                <?php else: ?>
                                    Bereits erfasst
                                <?php endif; ?>
                            </td>
                        </form>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php endif; ?>
    </body>
</html>