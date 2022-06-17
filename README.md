# DKFZ Intranet TYPO3

## Local setup

1. Clone repository
2. Run `ddev start`
3. Done.

## Example data

When starting the project for the first time, an example database is imported that includes a page structure, some content elements and users.

* Backend user: admin:changeme
* Frontend user: max@example.com:changeme

## Project structure

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

## Commands

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

## Feature Branch Deployment

