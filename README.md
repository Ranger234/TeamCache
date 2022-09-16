# Einfaches Script für einen OpenCaching Teamcache
Leg einen Cache mit 2 Startpunkten an, an denen je eine URL zu diesem Script zu finden ist (QR-Code, NFC oder was dir sonst so einfällt). Mach zudem ein Cache-Final, dessen Koordinaten du in der Datei coords.php ablegst.
Die Spieler müssen jetzt gleichzeitig (innerhalb von 2 Minuten) die URL öffnen und einen 4-stelligen Code eingeben, dann bekommen sie die Final-Koordinaten.

Viel Spaß beim legen und suchen

## Zur Vorbereitung

### Datei coords.php
Leg die Datei coords.php im Hauptverzeichnis des Scripts an und trag deine Finalkoordinaten dort ein.

### PHP-Min Version 8.0
Achte auf die PHP-Version deines Webservers:
Note that str_contains() function has been available since PHP 8.0.0

## Vorschläge zur Verbesserung
### Dynamische QR-Codes 
QR-Codes die zeitgesteuert zu variablen Links verweisen könnten sehr viel schwieriger erraten werden. Damit würde es nötig tatsächlich mit zwei Teams vor Ort zu sein und die Codes einzugeben.

### Dynamische Passworter
Zeitgesteuerte 2FA Codes, die es erschweren die Mechanik zu umgehen.

### Abschluß der Aufgabe
Verlinken auf eine neue Seite, die beiden Parteien die Final-Koordinaten (gleichzeitig) anzeigt und so eine Art Wettrennen möglich macht.
