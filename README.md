# DKFZ Intranet TYPO3

This is the main repository for the DKFZ TYPO3 project.

* Repository: [GitLab XIMA](https://git.xima.de/typo3/dkfz/dkfz-intranet-typo3) → [GitLab DKFZ](https://git.dkfz.de/dkfz/dkfz-t3-intranet) (Mirror)
* Staging-Instance: [master.dev.dkfz-intranet-typo3.xima.dev](https://master.dev.dkfz-intranet-typo3.xima.dev/)
* Feature-Branches overview: [GitLab Deployments](https://git.xima.de/typo3/dkfz/dkfz-intranet-typo3/-/environments)

## 1. Local setup

1. Clone repository (see 2. Git-Server)
2. Run `ddev start`
3. Done.

### 1.1. Asset-Building

All TypeScript and SCSS source files are located inside the `packages/xm_dkfz_net_site/Resources/Private` directory and are compiled via webpack.

* `npm run start` (watch task, used in *Development/Local* context)
* `npm run build` (build task, used in *Production* context)

## 2. Git-Server

There are currently two Gitlab installations where you can checkout the source code:

* [XIMA-Intern](https://git.xima.de/typo3/dkfz/dkfz-intranet-typo3) (**PRIMARY**)
* [DKFZ-Intern](https://git.dkfz.de/dkfz/dkfz-t3-intranet.git) (Mirror)

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

### 3.2. Staging database

To download the staging database, you can use deployer. These commands download the database and media files:

```
dep db:pull dev-t3-debian11-01-master
dep media:pull dev-t3-debian11-01-master
```

(Make sure you have authenticated your ddev container and added the `dep` command alias, see 5. Commands)

### 3.3. Production Artifacts

There is a deployer task to download the production database and files from the DKFZ GitLab via API:

```
dep reset:from_production_artifact -o DKFZ_ACCESS_TOKEN="<SEE KEEPASS>"
```

### 4. TYPO3 Styleguide

* [master.dev.dkfz-intranet-typo3.xima.dev/styleguide/themenseite](master.dev.dkfz-intranet-typo3.xima.dev/styleguide/themenseite)
* [master.dev.dkfz-intranet-typo3.xima.dev/styleguide/komponenten](master.dev.dkfz-intranet-typo3.xima.dev/styleguide/komponenten)

## 5. Project structure

All configurations are made for the production context. Configurations can be overridden via `.env` oder `dev.typoscript` files to fit your local ddev installation.

```
├── Tests
├── config
├── packages
│   ├── xm_dkfz_net_jobs
│   └── xm_dkfz_net_site
│   └── ...
├── public (Web root)
│   ├── typo3conf
│   │   ├── AdditionalConfiguration.php
│   │   └── LocalConfiguration.php
│   └── .htaccess
├── .env
├── composer.json
├── deploy.php
└── package.json
```

## 6. Commands

* `ddev` commands:
  * ```ddev ...```
  * ```ddev composer ...```
  * ```ddev typo3cms ...```
* `deployer` commands:
  * ```dep```
  * ```dep deploy-fast``` → select instance (e.g. master) deployment
  * ```dep db:pull``` → Download database
  * ```dep media:pull``` → Download fileadmin & co.

To add the `dep` alias, add this to your `~/.bashrc`:

```
alias dep="ddev exec vendor/bin/dep"
```

## 7. Tests

The pipelines are configured to run tests before deploying to master and production.

* phpstan
* phpfixer
* phplint
* Codeception (currently disabled in pipeline)

To run the tests locally, run `ddev composer run php:stan` and `ddev composer run php:fixer`.
