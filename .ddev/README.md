# DDEV

Unter macOS liefert [DDEV](https://confluence.xima.de/display/KBA/DDEV) eine höhere Performance als Docker-Compose.

## Lokale Installation

Für die **lokale Installation** mit DDEV müssen die folgenden Schritte durchgeführt werden:

1. [DDEV](https://confluence.xima.de/display/KBA/DDEV) installieren
2. DDEV starten

    ```bash
    $ ddev start
    ```

3. TYPO3 installieren
    ```bash
    $ ddev init
    ```
   
4. Nun ist die Seite über `prop.ddev.site` erreichbar.

### Optional

#### Layoutbuilder
      
1. LayoutBuilder initial installieren

    ```bash
    $ ddev composer-layoutbuilder i
    ```

2. Assets installieren/bauen

    ```bash
    $ ddev npm ci
    $ ddev grunt build
    $ ddev grunt staging
    ```

3. Der Layoutbuilder ist über `prop-layout.ddev.site` erreichbar.

#### Solr

Solr initial befüllen (indexieren):

Dazu im Backend Modul _Apache Solr > Index Queue_ die Index Queue initialisieren (zuerst: Roter Button _Clear Index Queue_, dann alle verfügbaren Queues initialisieren) und anschließend den Scheduler Task _Index Queue Worker (solr)_ manuell anstoßen (ggf. mehrmals). Die Suche sollte nun Ergebnisse liefern.

Das Solr Backend ist unter `http://prop.ddev.site:8983` zu erreichen.

### Zusätzliche Konfigurationen

Weitere Einstellungsmöglichkeiten, welche spezifisch für das lokale System hinsichtlich der [DDEV Konfiguration](https://ddev.readthedocs.io/en/latest/users/extend/config_yaml/) vorgenommen werden, müssen in einer `config.local.yaml` konfiguriert werden. Beispielsweise kann das NFS Mount aktiviert werden, um unter Mac eine bessere Performance zu erreichen.

```bash
nfs_mount_enabled: true
```

## Commands

[Offizielle Dokumentation](https://ddev.readthedocs.io/en/latest/)

- SSH in den Web Container

    ```bash
    $ ddev ssh
    ```

- Übersicht zum Projekt

    ```bash
    $ ddev describe [project-name]
    ```

- Alle DDEV Projekte

    ```bash
    $ ddev list
    ```

- `composer dump-autoload` im Web Container unter `htdocs/typo3`

    ```bash
    $ ddev composer-typo3 du
    ```

- `composer install` im Web Container unter `htdocs/typo3`

    ```bash
    $ ddev composer-typo3 i
    ```

- `composer update` im Web Container unter `htdocs/typo3`

    ```bash
    $ ddev composer-typo3 u
    ```

- TYPO3 Log auswerten (`tail`) im Web Container

    ```bash
    $ ddev log-typo3 [-f]
    ````

- `mysql` im DB Container

    ```bash
    $ ddev mysql [flags] [args]
    ```

- Führt das [db-sync-tool](https://github.com/jackd248/db-sync-tool) Skript zum Abgleich der Datenbank vom Zielsystem im Web Container aus. Im Anschluss erfolgt noch ein Update des Datenbankschemas. 

    ```bash
    $ ddev sync [stage|prod]
    ```
  
  Damit das benötigte Skript geladen wird, muss unter `/htdocs` (Deployment Ebene) ein `composer install` durchgeführt werden.

- Führt das [db-sync-tool](https://github.com/jackd248/db-sync-tool) Skript zum Erzeugen eines lokalen Datenbank Dumps aus. 

    ```bash
    $ ddev dump
    ```
  
  Damit das benötigte Skript geladen wird, muss unter `/htdocs` (Deployment Ebene) ein `composer install` durchgeführt werden.

- `npm ci` im Web Container

    ```bash
    $ ddev npm [flags] [args]
    ```

- `grunt` im Web Container

    ```bash
    $ ddev grunt [flags] [args]
    ```
