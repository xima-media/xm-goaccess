=========
Changelog
=========

Version 4.2.1 (September 2022)
-----------------------------
- [BUGFIX] Fix error logging when geocoding failed
- [BUGFIX] Show sorting "Distance" when distance search is activated (fix deprecation)

Version 4.2.0 (September 2022)
-----------------------------
- [FEATURE] Partial word search (find searchstrings inside words) without the need of using Sphinx
- [BUGFIX] Fix PHP8 issues
- [TASK] Add Services.yaml and use correct file name for SphinxApi class
- [TASK] Refactor BoostKeywords to use new event "MatchColumnsEvent"
- [TASK] Raise ke_search dependency to 4.5.0

Version 4.1.0 (December 2021)
-----------------------------
- [!!!] This version works with TYPO3 10 and 11 and drops support for TYPO3 9.
- [TASK] Mark as compatible to TYPO3 11.5
- [TASK] Move language files from xml to xlf
- [TASK] Move TypoScript configuration files to Configuration/TypoScript and rename the ending from txt to typoscript
- [TASK] Make Autocomplete function compatible to TYPO3 11
- [TASK] Optimize Autocomplete function in order to make it work with multiple search forms on the page
- [TASK] Do not use underscore for filter names in distance search filter. Underscores must not be used for filter names (due to the fact that the routing features uses underscores to flatten filter arrays).
- [TASK] Initialize customranking value only if it not has been set before

Version 4.0.1 (October 2021)
------------------------------
- [BUGFIX] Security Fix. Please update!

Version 4.0.0 (October 2021)
----------------------------
- [TASK] Use namespace "tpwd" for ke_search_premium classes
- [TASK] Use new namespace "tpwd" for ke_search classes (has changed in ke_search 4.0.0)
- [TASK] Require twpd/ke_search
- [TASK] Require ke_search version 4.0.1
- [TASK] Log sphinx errors to ke_search logfile
- [TASK] Change eID class signature in order to adapt remote indexer feature to TYPO3 10
- [TASK] Include fields for the result list in the default fields for the headless response

Version 3.4.2 (September 2021)
------------------------------
- [BUGFIX] Security Fix. Please update!

Version 3.4.1 (July 2021)
----------------------------
- [TASK] Add whitelist for headless response fields
- [TASK] Add hook for headless configuration

Version 3.4.0 (June 2021)
----------------------------
- [FEATURE] Add headless (JSON) response via MiddleWare
- [TASK] Add extension key to composer.json
- [TASK] Rearrange the settings in the extension manager (move settings from the basic tab to the tab they belong to)
- [TASK] Raise ke_search requirement to 3.8.1

Version 3.3.0 (January 2021)
----------------------------
[!!!] This version changes the database structure!

- [TASK] Add ke_search 3.1.5 requirement
- [TASK] Synonyms: Use words as synonyms only if the full word matches exactly (no part word matches)
- [BUGFIX] Fix TYPO3 requirement (works with 9, not only 10) in ext_emconf.php to match composer.json

Version 3.2.0 (July 2020)
-------------------------
- [BREAKING] Note: This version changes the database structure.
- [FEATURE] Add feature "Keyword Boost"
- [FEATURE] Add feature "Custom Ranking Boost"

Version 3.1.1 (January 2020)
----------------------------
- [TASK]   Set compatibility to TYPO3 10.4

Version 3.1.0 (January 2020)
----------------------------
This version works with TYPO3 9.5 and 10.2.

- [TASK]   Make distance search work with TYPO3 10.2, move TCA configuration to Configuration/TCA/Overrides, include static template automatically in ext_tables.conf (no need to add static template manually anymore), remove ext_tables.php.
- [TASK]   Make autocomplete feature work with TYPO3 10.2
- [TASK]   use ExtensionConfiguration API in ext_localconf.php.
- [TASK]   remove "use" statement in ext_localconf.php.
- [TASK]   use ExtensionConfiguration API in ext_tables.php.
- [TASK]   remove "use" statement in ext_tables.php.
- [BUGFIX] Fix check for valid URL in remote indexer.
- [TASK]   Do not use jQuery for the autocomplete feature but use plain javascript instead.
- [BUGFIX] remove wrong use statement in ModifySearchWords.php
- [TASK]   Fix deprecation, replace getUserObj with makeInstance.

Version 3.0.0 (January 2019)
----------------------------
This version works with TYPO3 9.5.

- [FEATURE] Make ke_search_premium compatible with TYPO3 9.5 and ke_search version 3.0.0
- [TASK] Use namespaced classes
- [TASK] move TCA to Configuration/TCA
- [TASK] use doctrine / query builder for database queries
- [TASK] remove ext_autoload.php
- [TASK] Move autocomplete javascript to the footer
- [BUGFIX] Fix including of typoscript code for autocomplete feature.

Version 2.1.0 (January 2018)
----------------------------
- [FEATURE] Add configuration option for Google API (you'll need two keys, one for maps and one for geocoding).
- [FEATURE] Use SSL for Google API access.
- [FEATURE] Make ke_search_premium compatible with TYPO3 8.7 and ke_search Version 2.6.2.
- [BUGFIX]  Fix bug "No columns definition in TCA table" in TYPO3 8.7

Version 2.0.0 (May 2016)
------------------------
- [FEATURE] Make ke_search_premium compatible with TYPO3 7.6 and ke_search Version 2.2.1.

Version 1.2.2
-------------
- bugfix release

Version 1.2.1, February 2015
----------------------------
- bugfix: fix typo
- bugfix: fix function header for google maps integration

Version 1.2.0, January 2015
---------------------------
- feature: Add remote indexer. This new indexer allows to import search results from a different TYPO3 website. This feature makes it possible to search two or more TYPO3 websites using one search form. On the remote TYPO3 website ke_search and ke_search_premium have to be installed and configured.
- task: add image for remote indexer
- task: add note how to activate "in word search" in extension manager.

Version 1.1.2
-------------
- feature: improve performance for large result sets by reducing the sorting to the first 1000 elements. After that, results will be unsorted.
- feature: improve autosuggest feature customization possibilites (new hook, pid checking).
- workaround for large result sets (> 50.000), TODO: optimize query so that this large amount of memory is not needed

Version 1.1.1, September 2014
-----------------------------
- Sphinx search bugfixing: Show correct number of results in filter options.
- feature: allow sphinx server name and port configuration in extension manager.
- feature: send email to administrator if sphinx reports an error.

Version 1.1.0, July 2014
------------------------
- Distance search and Google maps integration

Version 1.0.X
-------------
- Sphinx support
- Autocomplete
- Did you mean?
- Synonyms
