# DKFZ Intranet TYPO3

This is the main repository for the DKFZ TYPO3 project.

* Repository: [Gitlab](https://t3-gitlab-dev.xima.local/dkfz/dkfz-t3-intranet/)
* Staging-Instance: [stage.dkfz-typo3-dev.xima.local](https://stage.dkfz-typo3-dev.xima.local/)
* Feature-Branches: [fbd.dkfz-typo3-dev.xima.local](https://fbd.dkfz-typo3-dev.xima.local/)

## 1. Local setup

1. Clone repository (see 2. Git-Server)
2. Run `ddev start`
3. Done.

### 1.1. Asset-Building

All TypeScript and SCSS source files are located inside the `packages/xm_dkfz_net_prototype` directory and become compiled via webpack.

* `npm run start` (watch task, used in *Development/Local* context)
* `npm run build` (build task, used in *Production* context)

To build the patternlab prototype, run the following commands:

* `npm run serve` (watch task for patternlab)
* `npm run build:patternlab` (build task for patternlab)

## 2. Git-Server

There are currently two Gitlab installations where you can checkout the source code:

* [DKFZ-Intern](https://git.dkfz.de/dkfz/dkfz-t3-intranet.git) (**PRIMARY**)
* [XIMA-Intern](https://t3-gitlab-dev.xima.local)

To connect to the primary DKFZ-Repository via SSH, you need to adjust your SSH configuration (`~/.ssh/config`), since it does not use the default port 22:

```
Host git.dkfz.de
HostName git.dkfz.de
User git
Port 22022
IdentityFile ~/.ssh/id_rsa
```

## 3. Database

### 3.1. Starter database

When starting the project for the first time, an example database is imported that includes a page structure, some content elements and users.

* Backend user:
  * Username: admin
  * Password: changeme
* Frontend user:
  * Username: max@example.com
  * Password: changeme

### 3.2. Staging database (sync)

To download the staging database, you can use deployer. These commands download the database and media files:

```
dep db:pull staging
dep media:pull staging
```

(Make sure you have authenticated your ddev container and added the `dep` command alias, see 5. Commands)

### 3.3. TYPO3 Styleguide

* [https://stage.dkfz-typo3-dev.xima.local/styleguide/themenseite](https://stage.dkfz-typo3-dev.xima.local/styleguide/themenseite)
* [https://stage.dkfz-typo3-dev.xima.local/styleguide/komponenten](https://stage.dkfz-typo3-dev.xima.local/styleguide/komponenten)

## 4. Project structure

All configurations are made for the production context. Configurations can be overridden via `.env` oder `dev.typoscript` files to fit your local ddev installation.

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
  * ```ddev auth ssh``` → Add your SSH-Key to the container
* `deployer` commands:
  * ```dep```
  * ```dep deploy-fast staging``` → Staging deployment
  * ```dep deploy-fast feature -o branch=DKFZ-001``` → Feature branch deployment
  * ```dep db:pull staging``` → Download database
  * ```dep media:pull staging``` → Download fileadmin & co.

To add the `dep` alias, add this to your `~/.bashrc`:

```
alias dep="ddev exec vendor/bin/dep"
```

## 6. Tests

The pipelines are configured to run tests before deploying to staging.

* phpstan
* phpfixer
* Codeception (currently disabled in pipeline)

To run the tests locally, run `ddev composer run php:stan` and `ddev composer run php:fixer`.
