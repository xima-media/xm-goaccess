# DKFZ Intranet TYPO3

This is the main repository for the DKFZ TYPO3 project.

* Repository: [Gitlab](https://t3-gitlab-dev.xima.local/dkfz/dkfz-t3-intranet/)
* Staging-Instance: [stage.dkfz-typo3-dev.xima.local](https://stage.dkfz-typo3-dev.xima.local/)
* Feature-Branches: [fbd.dkfz-typo3-dev.xima.local](https://fbd.dkfz-typo3-dev.xima.local/)

## 1. Local setup

1. Clone repository (see 2. Git-Server)
2. Run `ddev start`
3. Done.

## 2. Git-Server

There a currently two Gitlab installations where you can checkout the source code:

* [XIMA-Intern](https://t3-gitlab-dev.xima.local)
* [DKFZ-Intern](https://git.dkfz.de/dkfz/dkfz-t3-intranet.git)

To connect to the DKFZ-Repository via SSH, you need to adjust your SSH configuration (`~/.ssh/config`), since it does not use the default port 22:

```
Host git.dkfz.de
HostName git.dkfz.de
User git
Port 22022
IdentityFile ~/.ssh/id_rsa
```

## 3. Database

When starting the project for the first time, an example database is imported that includes a page structure, some content elements and users.

* Backend user:
  * Username: admin
  * Password: changeme
* Frontend user:
  * Username: max@example.com
  * Password: changeme

## 3.1 TYPO3 Styleguide

* [https://stage.dkfz-typo3-dev.xima.local/styleguide/komponenten](https://stage.dkfz-typo3-dev.xima.local/styleguide/komponenten)


## 4. Project structure

```
├── Tests
│   └── codeception.yml
├── config
│   └── sites
├── packages
│   ├── xm_dkfz_net_jobs
│   │   ├── Classes
│   │   ├── composer.json
│   │   └── ext_localconf.php
│   ├── xm_dkfz_net_prototype
│   │   ├── source
│   │   ├── composer.json
│   │   └── package.json
│   └── xm_dkfz_net_site
│       ├── Configuration
│       ├── Resources
│       ├── composer.json
│       └── ext_localconf.php
├── public
│   ├── typo3conf
│   │   ├── AdditionalConfiguration.php
│   │   └── LocalConfiguration.php
│   └── .htaccess
├── .env
├── README.md
├── composer.json
├── deploy.php
└── package.json
```

## 5. Commands

* `ddev` commands:
  * ```ddev ...```
  * ```ddev composer ...```
  * ```ddev typo3cms ...```
* `deployer` commands:
  * ```dep```
  * ```dep deploy-fast staging``` → Production deployment
  * ```dep deploy-fast feature -o branch=DKFZ-001``` → Feature branch deployment
  * ```dep db:pull staging``` → Download database
  * ```dep media:pull staging``` → Download fileadmin & co.

To add the `dep` alias, add this to your `~/.bashrc`:

```
alias dep="ddev exec vendor/bin/dep"
```
