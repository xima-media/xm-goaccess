========
TYPO3 goaccess.io integration
========

Goaccess is an open source log analyzer which extracts metrics from a multitude of web log formats and visualizes them in your terminal or as html. Metrics can be exported to json and csv.
This TYPO3 extensions ships Dashboard widgets and a backend module to display various `goaccess.io <https://goaccess.io>`__ metrics.

..  container:: row m-0 p-0

..  container:: col-md-12 pl-0 pr-3 py-3 m-0

    ..  container:: card px-0 h-100

        ..  container:: card-body
        ..  image:: ./Images/backend-goaccess.jpg
            :class: with-shadow
            :alt: Plugin in the TYPO3 Backend

..  container:: col-md-12 pl-0 pr-3 py-3 m-0

    ..  container:: card px-0 h-100

        ..  container:: card-body
        ..  image:: ./Images/goaccess-module.jpg
            :class: with-shadow
            :alt: Plugin in the TYPO3 Backend

Installation
----------

.. code-block:: bash

    composer require xima/xm-goaccess


*   `Install and configure goaccess <https://goaccess.io/get-started>`__ to generate json and/or html output to a directory
*   Make sure the webserver user has read access to the generated files

Configuration
-------------

To enable the backend module, set the path to the generated html via extension configuration:

.. code-block:: bash

    $GLOBALS['TYPO3_CONF_VARS']['EXTENSIONS']['xm_goaccess']['html_path] = '/tmp/goaccess/goaccess.html';

To make the new dashboard widgets work, you need to pass the path to the generated json file:

.. code-block:: bash

    $GLOBALS['TYPO3_CONF_VARS']['EXTENSIONS']['xm_goaccess']['json_path] = '/tmp/goaccess/goaccess.json';


Usage
-----

You can add single widgets to your custom dashboard or use the preset that creates a new dashboard with all available goaccess integrations.

..  container:: card-body
..  image:: ./Images/backend-dashboard.jpg
    :class: with-shadow
    :alt: Add goaccess dashboard widgets


Goaccess installation on Debian/Ubuntu:
~~~~~~~~~~~~

The application is written in go and available from the standard repositories of multiple Linux distributions. A repository for Debian/Ubuntu is also provided. See the `official goaccess.io documentation <https://goaccess.io/get-started>`__ for a complete guide.

.. code-block:: bash

    apt install goaccess


HTML and JSON exports for the TYPO3 extension can be generated from Apache logs as follows:

.. code-block:: bash


    usr/bin/zcat --force /var/log/apache2/access_example.org.log* | \ # You usually want to parse rotated and gzipped logs as well.
        /usr/bin/goaccess -                                         \
        -o goaccess.html -o goaccess.json                           \ # Export processed metrics as html and json.
        --log-format=COMBINED                                       \ # Apache Combined Log Format. Custom log formats are supported, too.
        --ignore-crawlers                                           \ # Ignore web crawlers.
        --exclude-ip ::1 --exclude-ip 127.0.0.1                       # Exclude status checks originating from local ip addresses.

To refresh these files periodically, you might use a cronjob, e.g.:

.. code-block:: bash

    */15 * * * /usr/bin/mkdir -p /tmp/goaccess; chmod 750 /tmp/goaccess; /home/user/goaccess-generation.sh
