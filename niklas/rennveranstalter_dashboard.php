<?php
//Niklas Steigmiller
//Sessionstart und Einbindung der benötigten Dateien
session_start();

require_once "../Verbindung.php";
require_once "Rennveranstalter.php";
//Instanziierung der Rennveranstalterklasse
$rv = new Rennveranstalter();
//Wenn eine RID über POST übergeben wurde, wird diese in der Session gespeichert und zur Detailseite weitergeleitet
if (isset($_POST['rid'])) {
    $_SESSION['rid'] = $_POST['rid'];
    header("Location: rennveranstalter_rennen_detail.php");
    exit();
}
//Abrufen des VName und NName aus der Session, um die Rennen des angemeldeten Rennveranstalters zu holen
$VName = $_SESSION['vname'];
$NName = $_SESSION['nname'];
//Abrufen der Rennen des Rennveranstalters mit der Funktion getRennen der Rennveranstalterklasse
$rennen = $rv->getRennen($VName, $NName);
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Rennveranstalter Dashboard</title>
    </head>
    <body>
        <h1>Rennveranstalter Dashboard</h1>
        <a href="index.php">Zurück</a>
        <a href="rennveranstalter_rennen_anlegen.php"><button>Rennen anlegen</button></a>

        <h2>Meine Rennen</h2>

        <?php if (empty($rennen)): ?>
            <p>Keine Rennen angelegt.</p>
            <!--Wenn keine Rennen vorhanden sind, wird eine entsprechende Nachricht angezeigt. Ansonsten wird eine Tabelle mit den Rennen und einem Button für Details angezeigt-->
        <?php else: ?>
            <table border="1">
                <tr>
                    <th>RID</th>
                    <th>Datum</th>
                    <th>Startort</th>
                    <th>Kilometer</th>
                    <th>Höhenmeter</th>
                    <th>Max. Steigung %</th>
                    <th>Aktion</th>
                </tr>
            <!--Schleife zum Durchlaufen der Rennen und Anzeigen in der Tabelle-->
                <?php foreach ($rennen as $r): ?>
                    <tr>
                        <td>
                            <?php echo htmlspecialchars($r['rid'], ENT_QUOTES, 'UTF-8'); ?>
                        </td>
                        <td>
                            <?php echo htmlspecialchars($r['datum'], ENT_QUOTES, 'UTF-8'); ?>
                        </td>
                        <td>
                            <?php echo htmlspecialchars($r['startort'], ENT_QUOTES, 'UTF-8'); ?>
                        </td>
                        <td>
                            <?php echo htmlspecialchars($r['km'], ENT_QUOTES, 'UTF-8'); ?>
                        </td>
                        <td>
                            <?php echo htmlspecialchars($r['hoehe'], ENT_QUOTES, 'UTF-8'); ?>
                        </td>
                        <td>
                            <?php echo htmlspecialchars($r['steigung'], ENT_QUOTES, 'UTF-8'); ?>
                        </td>
                        <!--Formular mit einem versteckten Input für die RID und einem Button, um zur Detailseite des Rennens zu gelangen-->
                        <td>
                            <form method="post" action="">
                                <input type="hidden" name="rid"
                                       value="<?php echo htmlspecialchars($r['rid'], ENT_QUOTES, 'UTF-8'); ?>">
                                <button type="submit">Details</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php endif; ?>
    </body>
</html>