<?php
$host      = "localhost";   
$user      = "gruppe19";    
$passwort  = "{yI)X2)vN7w1";
$datenbank = "gruppe19";          
 
$verbindung = mysqli_connect($host, $user, $passwort, $datenbank);
 
if (!$verbindung) {
    die("Verbindung fehlgeschlagen: " . mysqli_connect_error());
}
echo "Verbindung erfolgreich!";
?>