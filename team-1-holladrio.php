<?php
// Finalkoordinaten festlegen
$nord = "NXX° XX.XXX";
$east = "EYY° YY.YYY";

// gesamter Pfad zum Dateinamen kann noetig sein
$cachecode1 = 'cachcode1.txt';
$cachecode2 = 'cachcode2.txt';
$ok1 = 'ok1.txt';
$ok2 = 'ok2.txt';
$zeitstempel = 'zeitstempel.txt';

date_default_timezone_set("Europe/Berlin");

// Überprüft Eingabewerte für $textfeld auf Korrektheit.
function pruefe_textfeld($val) {
   $msg = "";
   if (strlen($val) < 4)
      $msg .= "Die Eingabe muss mindestens 4 Zeichen lang sein.\n";

   if (preg_match("/\s/", $val))
      $msg .= "Die Eingabe darf keine Leerzeichen "
             ."oder Tabulatoren enthalten.\n";

   return $msg;
}

// Zurücksetzen des Spiels
function zuruecksetzen(): void {
    global $cachecode1, $cachecode2, $ok1, $ok2, $zeitstempel;
    unlink($cachecode1);
    unlink($cachecode2);
    unlink($ok1);
    unlink($ok2);
    unlink($zeitstempel);
}


// Session starten und auf 180 Sekunden begrenzen
session_set_cookie_params(180, "/");
session_start();

// Für jedes Formularfeld werden nun ein oder mehrere
// Validatoren aufgerufen und das Ergebnis der Überprüfung
// gemerkt.
$valid = true;
if (isset($_REQUEST["textfeld"])) {
   $error["textfeld"] = pruefe_textfeld($_REQUEST["textfeld"]);
   if ($error["textfeld"] != "") {
       $valid = false;
   }
}

// falls die Dateien zum zwischenspeichern älter als 180 Sekunden sind, dann zurücksetzen
if(time() - filemtime($zeitstempel) >= 180) {
    zuruecksetzen();
}

// existiert der Zeitstempel bereits?
if (!file_exists($zeitstempel)) {
    // wenn nicht, komplett zurücksetzen, spiel starten
    zuruecksetzen();
    $startZeit = strtotime('+120 seconds');
    if (false === file_put_contents($zeitstempel, $startZeit)) {
        die('Konnte die Zeit nicht speichern.');
    }
    $dercode1 = rand(1000, 9999);
    if (false === file_put_contents($cachecode1, $dercode1)) {
        die('Konnte die code1 nicht speichern.');
    }
    $dercode2 = rand(1000, 9999);
    if (false === file_put_contents($cachecode2, $dercode2)) {
        die('Konnte die code2 nicht speichern.');
    }
} else {
    $startZeit = file_get_contents($zeitstempel);
    if (false === $startZeit) {
        zuruecksetzen();
        die('Konnte die Zeit nicht lesen.');
    }
    $dercode1 = file_get_contents($cachecode1);
    if (false === $dercode1) {
        zuruecksetzen();
        die('Konnte code1 nicht lesen.');
    }
    $dercode2 = file_get_contents($cachecode2);
    if (false === $dercode2) {
        zuruecksetzen();
        die('Konnte code2 nicht lesen.');
    }
}

// Welcher Spieler sind wir?
if (!isset($_SESSION['spieler'])) {
    if (str_contains($_SERVER['REQUEST_URI'], 'team-1')) {
        $_SESSION['spieler'] = 1;
    } else {
        $_SESSION['spieler'] = 2;
    }
}

// Webseite generieren und für spieler ein oder zwei einrichten
echo '<!DOCTYPE html><html><head><meta charset="utf-8" /><title>OC15A33</title><meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1"></head><body>';
echo '<p>Willkommen bei OC15A33</p>';
echo '<p>Du bist - lass mich nachsehen ...</p>';
echo "<p>Spieler ${_SESSION['spieler']}</p>";
echo '<p>Dein Code ist: ' . ($_SESSION['spieler'] === 1 ? $dercode1 : $dercode2 ). '</p>';

echo '<p id="spielzeit"></p>';
?>
<script type="text/javascript">
    var countDownDate = <?php echo $startZeit ?> * 1000;
    
    var now = <?php echo time() ?> * 1000;

    var x = setInterval(function() {

        now = now + 1000;

        var distance = countDownDate - now;

        var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        var seconds = Math.floor((distance % (1000 * 60)) / 1000);

        document.getElementById("spielzeit").innerHTML = minutes + "m " + seconds + "s ";

        if (distance < 0) {
            clearInterval(x);
            document.getElementById("spielzeit").innerHTML = "Zeit abgelaufen";
        }
    }, 1000);
</script>
<form action="<?php print $_SERVER["PHP_SELF"]; ?>" method="post">
<input type="text"
       name="textfeld"
       value="<?php print htmlspecialchars($_REQUEST["textfeld"]); ?>"><br>
<?php
// Ggf. Fehlermeldung ausdrucken.
if (isset($error["textfeld"]))
   echo '<p>'.$error["textfeld"].'</p>';
?>
<input type="submit"
       name="do_form_x"
       value="Absenden">
</form>
<hr>
<?php

if ($valid and isset($_REQUEST["do_form_x"])) {
    // verschiedene Prüfungen ob die Zeit nicht abgelaufen ist und ob die codes richtig eingegeben wurden
    $differenz = $startZeit - time();
    if ($differenz <= 0) {
        echo '<p>Zeit abgelaufen</p>';
    } else {
        if ($_SESSION['spieler'] === 1) {
            if ($_REQUEST["textfeld"] == $dercode1) {
                echo "<p>Spieler 1 richtig</p>";

                $file_handle = fopen($ok1, 'w');
                fwrite($file_handle, "Spieler1");
                fclose($file_handle);

                if (file_exists($ok2)) {
                    echo "<p>Spieler 2 hat schon getippt</p>";
                    echo "<p>Koordinaten:</p>";
                    echo "<p>" . $nord . "</p>";
                    echo "<p>" . $east . "</p>";
                    zuruecksetzen();
                    session_destroy();
                } else {
                    echo "<p>Warte auf Spieler 2</p>";
                }
            } else {
                echo "Spieler 1 falsch";
            }
        } elseif ($_SESSION['spieler'] === 2) {
            if ($_REQUEST["textfeld"] == $dercode2) {
                echo "<p>Spieler 2 richtig</p>";

                $file_handle = fopen($ok2, 'w');
                fwrite($file_handle, "Spieler2");
                fclose($file_handle);

                if (file_exists($ok1)) {
                    echo "<p>Spieler 1 hat schon getippt</p>";
                    echo "<p>Koordinaten: </p>";
                    echo "<p>" . $nord . "</p>";
                    echo "<p>" . $east . "</p>";
                    zuruecksetzen();
                    session_destroy();
                } else {
                    echo "<p>Warte auf Spieler 1</p>";
                }
            } else {
                echo "Spieler 2 falsch";
            }
        } else {
            echo "Kein Spieler";
        }
    }
}

echo '</body></html>';
