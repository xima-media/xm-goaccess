# TYPO3 CMS 10 LTS - {{CUSTOMER}}

![alt text](http://docker.xima.local:10500/api/project_badges/measure?project={{PROJECT_NAME}}&metric=alert_status)
![alt text](http://docker.xima.local:10500/api/project_badges/measure?project={{PROJECT_NAME}}&metric=security_rating)
![alt text](http://docker.xima.local:10500/api/project_badges/measure?project={{PROJECT_NAME}}&metric=reliability_rating)
![alt text](http://docker.xima.local:10500/api/project_badges/measure?project={{PROJECT_NAME}}&metric=coverage)
[SonarCube](http://sonarqube.xima.ws/dashboard?id={{PROJECT_NAME}})

## Inhalt
* [Wozu dient das Projekt?](#intro)
* [Technische Dokumentation](#technical-doc)
* [Installation](#installtion)
* [Deployment](#deployment)
    * [RSYNC](#deployment-rsync)

<a name="intro"></a>
## Wozu dient das Projekt?

Dieses Projekt ist das TYPO3 Basisprojekt für die Webseite [{{WEBSITE}}]({{WEBSITE}}).

<a name="technical-doc"></a>
## Technische Dokumentation
* Die Technische Dokumentation befindet sich im [Confluence]({{LINK_TECH_DOKU}}).

<a name="installtion"></a>
## Installation

1. Git-Repository klonen:

    ````shell script
    $ git clone {{GIT_REPO_URL}}
    ````

2. Lokale Entwicklungsumgebung, wie unter [.ddev/README.md](.ddev/README.md) beschrieben, einrichten.

<a name="deployment"></a>
## Deployment

Das Deployment erfolgt mittles [GitLab-CI](https://docs.gitlab.com/ee/ci/) und dem Tool 
[Phing Typo3 Deployer](https://github.com/hirnsturm/phing-typo3-deployer). Die Konfiguration ist in der 
Datei `.gitlab-ci.yml` zu finden.

<a name="deployment-rsync"></a>
#### RSYNC

Die Konfiguration der RSYNC-Excludes erfolgt je Stage in den folgenden Dateien:

```bash
deployment/rsync/
    dev_exclude.txt
    prod_exclude.txt
    stage_exclude.txt
```
