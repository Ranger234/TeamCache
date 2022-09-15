<?php
// Session starten und auf 180 Sekunden begrenzen
session_set_cookie_params(180, "/");
session_start();

// Finalkoordinaten festlegen
$nord = "NXX° XX.XXX";
$east = "EYY° YY.YYY";
$koords = $nord . " " . $east;

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

// Für jedes Formularfeld werden nun ein oder mehrere
// Validatoren aufgerufen und das Ergebnis der Überprüfung
// gemerkt.
$valid = true;
if (isset($_REQUEST["textfeld"])) {
   $error["textfeld"] = pruefe_textfeld($_REQUEST["textfeld"]);
   if ($error["textfeld"] != "")
      $valid = false;
}

// gesamter Pfad zum Dateinamen kann noetig sein
$cachecode1 = 'cachcode1.txt';
$cachecode2 = 'cachcode2.txt';
$ok1 = 'ok1.txt';
$ok2 = 'ok2.txt';
$zeitstempel = 'zeitstempel.txt';

// existiert der Zeitstempel bereits?
if (! file_exists($zeitstempel)) {
    // wenn nicht, anlegen
    $file_handle = fopen($zeitstempel, 'r');
    //und in $_SESSION einlesen
    $_SESSION['zeit'] = fread($file_handle, filesize($zeitstempel));
    $_SESSION['zeit'] = strtotime('+120 seconds');
    fclose($file_handle);
}


// falls die Dateien zum zwischenspeichern älter als 180 Sekunden sind, dann löschen (funktioniert noch nicht)
if(time() - filemtime($cachecode1) >= 180) { unlink($cachecode1); }
if(time() - filemtime($cachecode2) >= 180) { unlink($cachecode2); }
if(time() - filemtime($ok1) >= 180) { unlink($ok1); }
if(time() - filemtime($ok2) >= 180) { unlink($ok2); }
if(time() - filemtime($zeitstempel) >= 180) { unlink($zeitstempel); }

// Webseite generieren und für spieler ein oder zwei einrichten
echo '<!DOCTYPE html><html><head><meta charset="utf-8" /><title>OC15A33</title><meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1"></head><body>';
echo '<p>Willkommen bei OC15A33</p>';
echo '<p>Du bist - lass mich nachsehen ...</p>';
//echo time() - filemtime($cachecode1);

// wenn die Datei cachecode1 existiert und wir nicht in der Session von Spieler 1 sind, sind wir Spieler 2
if ((file_exists($cachecode1)) && ($_SESSION['spieler1'] != 1)) {
    // Werte für Spieler 2 aus den Dateien einlesen
    $file_handle = fopen($cachecode2, 'r');
    $dercode2 = fread($file_handle, filesize($cachecode2));
    fclose($file_handle);

    $file_handle = fopen($zeitstempel, 'r');
    $_SESSION['zeit'] = fread($file_handle, filesize($zeitstempel));
    fclose($file_handle);

    $_SESSION['spieler2'] = 2;
    $_SESSION['dercode2'] = $dercode2;
    echo '<p>Spieler 2</p>';
    echo '<p>Dein Code ist: ' . $dercode2 . '</p>';

// wenn die Datei cachcode1 nicht existiert sind wir Spieler 1
} elseif (! file_exists($cachecode1)) {
    
    $dercode1 = rand(1000, 9999);
    $dercode2 = rand(1000, 9999);
        
    $file_handle = fopen($cachecode1, 'w');
    fwrite($file_handle, $dercode1);
    fclose($file_handle);
    
    $file_handle = fopen($cachecode2, 'w');
    fwrite($file_handle, $dercode2);
    fclose($file_handle);
    
    if (! file_exists($zeitstempel)) {
        $file_handle = fopen($zeitstempel, 'w');
        fwrite($file_handle, $_SESSION['zeit']);
        fclose($file_handle);
     }

    $_SESSION['spieler1'] = 1;
    $_SESSION['dercode1'] = $dercode1;
    echo '<p>Spieler 1</p>';
    echo '<p>Dein Code ist: ' . $dercode1 . '</p>';
    if ( ! $dercode1 ) { echo '<p>Dein Code ist: ' . $_SESSION['dercode1']  . '</p>'; }
     
}

echo '<p id="spielzeit"></p>';
date_default_timezone_set("Europe/Berlin"); 
if (! file_exists($zeitstempel)) {
    $_SESSION['zeit'] = strtotime('+120 seconds');
}
?>
<script type="text/javascript">
    var countDownDate = <?php echo $_SESSION['zeit'] ?> * 1000;
    
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
<form action="<?php print $_SERVER["PHP_SELF"]; ?>">
<input type="text"
       name="textfeld"
       value="<?php print htmlspecialchars($_REQUEST["textfeld"]); ?>"><br>
<?php
// Ggf. Fehlermeldung ausdrucken.
if ($error["textfeld"] != "")
   print $error["textfeld"];
?>
<input type="submit"
       name="do_form_x"
       value="Absenden">
</form>
<hr>
<?php

if ($valid and isset($_REQUEST["do_form_x"])) {
    // verschiedene Prüfungen ob die Zeit nicht abgelaufen ist und ob die codes richtig eingegeben wurden
    $file_handle = fopen($zeitstempel, 'r');
    $diezeit = fread($file_handle, filesize($zeitstempel));
    fclose($file_handle);

    $differenz = $diezeit - time();

    if ($_SESSION['spieler1'] == 1) {
        if ($differenz <= 0) {
            echo '<p>Zeit abgelaufen</p>';
        } else {
            if ($_REQUEST["textfeld"] == $_SESSION['dercode1']) {
                echo "<p>Spieler 1 richtig</p>";

                $file_handle = fopen($ok1, 'w');
                fwrite($file_handle, "Spieler1");
                fclose($file_handle);

                if (file_exists($ok2)) {
                    echo "<p>Spieler 2 hat schon getippt</p>";
                    echo "<p>Koordinaten:</p>";
                    echo "<p>" . $nord . "</p>";
                    echo "<p>" . $east . "</p>";
                    unlink($cachecode1);
                    unlink($cachecode2);
                    unlink($ok1);
                    unlink($ok2);
                    unlink($zeitstempel);
                    session_destroy();
               } else {
                    echo "<p>Warte auf Spieler 2</p>";
               }
            } else {
                echo "Spieler 1 falsch";
            }
        }
    } elseif ($_SESSION['spieler2'] == 2) {
        if ($differenz <= 0) {
	        echo '<p>Zeit abgelaufen</p>';
        } else {
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
                    unlink($cachecode1);
                    unlink($cachecode2);
                    unlink($ok1);
                    unlink($ok2);
                    unlink($zeitstempel);
                    session_destroy();
                } else {
                    echo "<p>Warte auf Spieler 1</p>";
                }
            } else {
	            echo "Spieler 2 falsch";
            }
        }
    } else {
        echo "Kein Spieler";
    }
}

echo '</body></html>';
