#!/usr/bin/bash
datei1="cachcode1.txt"
datei2="cachcode2.txt"
datei3="zeitstempel.txt"
jetzt=$(date "+%s")
if test -f ; then
    alter1=$(date -r $datei1 "+%s")
    unter1=$(( jetzt - alter1 ))
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
