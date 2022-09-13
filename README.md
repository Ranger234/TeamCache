# Einfaches Script für einen OpenCaching Teamcache
Das Script funktioniert wenn der Vorgang wie vorgesehen abgeschlossen wird. Beim Abbruch oder Zeitüberschreitung bleiben die abgelegten codes und der Zeitstempel erhalten, diese werden dann via Crontab gelöscht

## Was bisher funktioniert
An zwei verschiedenen Orten befindet sich ein QR-Code, der einen (statischen) Link enthält. Dieser Link zeigt auf dieses PHP-Script. 

Die Spieler müsssen einen vom Script generierten und als Datei abgelegten Code in das dafür vorgesehene Feld eingeben und ein einfaches HTML Formular absenden. Parallel wird ein Zeitstempel als Datei abgelegt.

Schaffen die Spieler es die Codes innehalb der vorgesehenen Zeit einzugeben, bekommen sie GPS - Koordianten angezeigt.


## Vorschläge zur Verbesserung
### Dynamische QR-Codes 
QR-Codes die zeitgesteuert zu variablen Links verweisen könnten sehr viel schwieriger erraten werden. Damit würde es nötig tatsächlich mit zwei Teams vor Ort zu sein und die Codes einzugeben.

### Dynamische Passworter
Zeitgesteuerte 2FA Codes, die es erschweren die Mechanik zu umgehen.

### Abschluß der Aufgabe
Verlinken auf eine neue Seite, die beiden Parteien die Final-Koordinaten (gleichzeitig) anzeigt und so eine Art Wettrennen möglich macht.
