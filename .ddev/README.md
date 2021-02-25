# DDEV Entwicklungsumgebung

## Lokale Installation TYPO3

Für die **lokale Installation** mit DDEV müssen die folgenden Schritte durchgeführt werden:

1. [DDEV](https://confluence.xima.de/display/KBA/DDEV) installieren

2. DDEV starten

    ```bash
    $ ddev start
    ```
   
3. TYPO3 Instanz initial installieren
   
   ```bash
   $ ddev init
   ```
   
4. Nun ist die Seite über `{{ABBREVIATION}}.ddev.site` erreichbar.  

    Der angelegte TYPO3 Backend Admin hat folgende Credentials:

    **Benutzername:** admin  
    **Passwort:** password  
    **Install Tool:** password

## Weitere DDEV Commands

Weitere DDEV Commands können im Terminal über folgenden Befehl aufgelistet werden:

```bash
$ ddev
```
