<?php
//Niklas Steigmiller

session_start();

require_once "../Verbindung.php";
require_once "Rennveranstalter.php";

$rv = new Rennveranstalter();

$fehler   = "";
$erfolg   = "";
$VName    = "";
$NName    = "";
$Kennwort = "";
//Überprüfen, ob das Formular gesendet wurde. Wenn ja, werden die Eingaben validiert und der Rennveranstalter wird in der Datenbank registriert, wenn er noch nicht existiert. Fehler- und Erfolgsmeldungen werden entsprechend gesetzt.
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require_once "../Verbindung.php";
    //Die Eingaben werden getrimmt, um unnötige Leerzeichen zu entfernen.
    $VName    = trim($_POST['vorname']);
    $NName    = trim($_POST['nachname']);
    $Kennwort = trim($_POST['kennwort']);
    //Wenn eines der Felder leer ist, wird eine Fehlermeldung gesetzt. Ansonsten wird überprüft, ob ein Rennveranstalter mit diesem Namen bereits existiert.
    if (empty($VName) || empty($NName) || empty($Kennwort)) {
        $fehler = "Alle Felder müssen ausgefüllt werden.";
    } else {

        
        
        //Die Methode registrieren der Rennveranstalterklasse wird aufgerufen, um den neuen Rennveranstalter in der Datenbank zu speichern. Wenn ein Rennveranstalter mit diesem Namen bereits existiert, wird eine Fehlermeldung zurückgegeben. Andernfalls wird null zurückgegeben, was bedeutet, dass die Registrierung erfolgreich war.
         $fehler_oder_null = $rv->registrieren($VName, $NName, $Kennwort);

        if ($fehler_oder_null !== null) { // Wenn ein Rennveranstalter mit diesem Namen bereits existiert, wird eine Fehlermeldung gesetzt.
            $fehler = $fehler_oder_null;
        } else { // Wenn die Registrierung erfolgreich war, werden die Eingabefelder geleert und eine Erfolgsmeldung gesetzt.
    
            $erfolg   = "Rennveranstalter erfolgreich registriert.";
            $VName    = "";
            $NName    = "";
            $Kennwort = "";
            $hash     = "";
        }
    }
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Rennveranstalter registrieren</title>
    </head>
    <body>
        <a href="index.php">Zurück</a>
        
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

            <button type="submit">Registrieren</button> 
        </form>
    </body>
</html> 