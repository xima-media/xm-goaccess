# Projekt-Initialisierung

## Vorgehen

Für eine __saubere__ Projekt-Initialisierung sind folgende Kommandozeilenbefehle auszuführen:

````shell script
$ python3 -m venv ./venv
$ venv/bin/pip3 install -r ./init/requirements.txt
$ ./init/init.py
````

Anschließend ist das Projekt vollständig initialisiert und kann nun individuell erweitert werden.

## Konfiguration

Die Konfiguration der Parameterabfrage und Ersetzungen erfolgt in der Datei `init.yaml`.

## Tests

Zum automatisierten Testen der Projekt-Initialisierung muss folgendes Skript vom Projekt-Root aus ausgeführt werden:

> Achtung!
>
> Bei der Frage 'Push changes to origin master?' sollte im Test immer 'n' gewählt werden. 

````shell script
$ ./init/test.sh
````
 
