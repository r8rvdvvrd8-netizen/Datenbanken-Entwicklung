<?php
//Niklas Steigmiller
class Rennveranstalter {
    //Login-Funktion für Rennveranstalter mit hash-Überprüfung
    public function login($vname, $nname, $kennwort) {
        global $connection; // Globale Datenbankverbindung aus Verbindung.php
        //Vorbereiten der SQL-Anfrage, um den Hash des Kennworts für den gegebenen VName und NName zu holen schuzt vor SQL-Injection
        $stmt = mysqli_prepare($connection,
            "SELECT Kennwort FROM Rennveranstalter WHERE VName = ? AND NName = ?"
        );
        
        mysqli_stmt_bind_param($stmt, "ss", $vname, $nname); 
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $hash);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);
        //Überprüfen, ob der Hash existiert und ob das eingegebene Kennwort mit dem Hash übereinstimmt
        if ($hash && password_verify($kennwort, $hash)) {
            return true;
        }
        return false;
    }
    //Registrierungsfunktion für Rennveranstalter mit Überprüfung auf bestehende Einträge und Hashing des Kennworts
    public function registrieren($vname, $nname, $kennwort) {
        global $connection;
        $stmt = mysqli_prepare($connection,
            "SELECT COUNT(*) FROM Rennveranstalter WHERE VName = ? AND NName = ?"
        );
        mysqli_stmt_bind_param($stmt, "ss", $vname, $nname);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $existiert);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);

        if ($existiert > 0) {
            return "Ein Rennveranstalter mit diesem Namen existiert bereits.";
        }
        else {
        $hash = password_hash($kennwort, PASSWORD_DEFAULT);
        $stmt = mysqli_prepare($connection,
            "INSERT INTO Rennveranstalter (VName, NName, Kennwort) VALUES (?, ?, ?)"
        );
        mysqli_stmt_bind_param($stmt, "sss", $vname, $nname, $hash);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        return null;}
    }
    //Funktion zum Abrufen aller Rennen eines Rennveranstalters
    public function getRennen($vname, $nname) {
      Global    $connection;
        $stmt = mysqli_prepare($connection,
            "CALL RennenVonVeranstalter(?, ?)" //Aufruf der gespeicherten Prozedur, um die Rennen des Rennveranstalters zu holen
        );
        mysqli_stmt_bind_param($stmt, "ss", $vname, $nname);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $rid, $datum, $startort, $km, $hoehe, $steigung); //Bindet die Ergebnisse der Prozedur an Variablen

        $rennen = []; //Array zum Speichern der Rennen
        while (mysqli_stmt_fetch($stmt)) { //Schleife zum Abrufen aller Ergebnisse und Speichern in einem Array
            $rennen[] = [
                'rid'      => $rid,
                'datum'    => $datum,
                'startort' => $startort,
                'km'       => $km,
                'hoehe'    => $hoehe,
                'steigung' => $steigung
            ];
        }
        mysqli_stmt_close($stmt); //Schließen der Anweisung

        return $rennen;
    }
    //Funktion zum Anlegen eines neuen Rennens mit den angegebenen Parametern
    public function rennenAnlegen($datum, $startort, $km, $hoehe, $steigung, $vname, $nname)     {
            global $connection;
        $stmt = mysqli_prepare($connection,
            "CALL RennenAnlegen(?, ?, ?, ?, ?, ?, ?)" //Aufruf der gespeicherten Prozedur zum Anlegen eines neuen Rennens mit den übergebenen Parametern
        );
        mysqli_stmt_bind_param($stmt, "ssdidss",
            $datum, $startort, $km, $hoehe, $steigung, $vname, $nname
        );
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }
    //Funktion zum Abrufen aller Fahrer eines bestimmten Rennens anhand der RID
    public function getFahrer($rid) {
        global $connection;
        $stmt = mysqli_prepare($connection,
            "CALL FahrerVonRennen(?)"//Aufruf der gespeicherten Prozedur zum Abrufen aller Fahrer eines Rennens anhand der RID
        );
        mysqli_stmt_bind_param($stmt, "i", $rid);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt,
            $startnr, $name, $mid, $tname, $platzierung, $fahrzeit
        );

        $fahrer = []; //Array zum Speichern der Fahrer
        while (mysqli_stmt_fetch($stmt)) { //Schleife zum Abrufen aller Ergebnisse und Speichern in einem Array
            $fahrer[] = [
                'startnr'     => $startnr,
                'name'        => $name,
                'mid'         => $mid,
                'tname'       => $tname,
                'platzierung' => $platzierung,
                'fahrzeit'    => $fahrzeit
            ];
        }
        mysqli_stmt_close($stmt); //Schließen der Anweisung

        return $fahrer;
    }
    //Funktion zum Erfassen eines Ergebnisses für einen Fahrer in einem Rennen mit den angegebenen Parametern
    public function ergebnisErfassen($tname, $mid, $rid, $platzierung, $fahrzeit ){
        global $connection;
    $stmt = mysqli_prepare($connection,
            "CALL ErgebnisErfassen(?, ?, ?, ?, ?)" //Aufruf der gespeicherten Prozedur zum Erfassen eines Ergebnisses für einen Fahrer in einem Rennen mit den übergebenen Parametern
        );
        mysqli_stmt_bind_param($stmt, "siiis",
            $tname, $mid, $rid, $platzierung, $fahrzeit
        );
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }
}
?>
