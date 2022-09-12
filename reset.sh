#!/usr/bin/bash
#
# Da das löschen der Dateien im Script nur funktioniert, wenn
# der Vorgang ordnungsgemäß abgeschlossen wird, lösche ich die 
# Dateien via cron-job (alle 5 Minuten)
#
datei1="cachcode1.txt"
datei2="cachcode2.txt"
datei3="zeitstempel.txt"

# Aktuelle Zeit feststellen (epoch)
jetzt=$(date "+%s")

# Wenn die Datei existiert.
if test -f $datei1; then
    # dann Zeitstempel der Datei in alter1 (epoch)
    alter1=$(date -r $datei1 "+%s")
    # alter der Datei in Sekunden 
    unter1=$(( jetzt - alter1 ))
    # wenn die Datei älter als 180 Sekunden dann löschen
    if [ $unter1 -gt 180 ];
       then 
           rm $datei1
       fi
fi
if test -f $datei2; then
    alter2=$(date -r $datei2 "+%s")
    unter2=$(( jetzt - alter2 ))
    if [ $unter2 -gt 180 ];
       then 
           rm $datei2
       fi
fi
if test -f $datei3; then
    alter3=$(date -r $datei3 "+%s")
    unter3=$(( jetzt - alter3 ))
    if [ $unter2 -gt 180 ];
       then 
           rm $datei3
       fi
fi
exit
