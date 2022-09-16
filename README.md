# Einfaches Script für einen OpenCaching Teamcache
Das Script funktioniert wenn der Vorgang wie vorgesehen abgeschlossen wird. Beim Abbruch oder Zeitüberschreitung bleiben die abgelegten codes und der Zeitstempel erhalten, diese werden dann via Crontab gelöscht

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
