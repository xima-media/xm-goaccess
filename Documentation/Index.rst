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

The application is written in go and available from the standard repositories of multiple Linux distributions. A repository for Debian/Ubuntu is also provided.

Installation
----------

.. code-block:: bash

    composer require xima/xm-goaccess


*   `Install and configure goaccess <https://goaccess.io/get-started>`__ to generate json and/or html output to a directory
*   Make sure the webserver user has read access to the generated files


Installation on Debian/Ubuntu:
~~~~~~~~~~~~

.. code-block:: bash

    apt install goaccess


Html and json exports for the TYPO3 extension can be generated from Apache logs as follows:

.. code-block:: bash

    # You usually want to parse rotated nd gzipped logs as well.
    usr/bin/zcat --force /var/log/apache2/access_example.org.log* |

    # Export processed metrics as html and json.
    /usr/bin/goaccess -
    -o goaccess.html -o goaccess.json

    # Apache Combined Log Format. Custom log formats are supported, too.
    --log-format=COMBINED

    # Ignore web crawlers.
    --ignore-crawlers

    # Exclude status checks originating from local ip addresses.
    --exclude-ip ::1 --exclude-ip 127.0.0.1

To refresh these files periodically, you might use a cronjob.

Configuration
-------------

To enable the backend module, set the path to the generated html via extension configuration:

.. code-block:: bash

    $GLOBALS['TYPO3_CONF_VARS']['EXTENSIONS']['xm_goaccess']['html_path] = '/tmp/goaccess/goaccess.html';

To make the new dashboard widgets work, you need to pass the path to the generated json file:

.. code-block:: bash

    $GLOBALS['TYPO3_CONF_VARS']['EXTENSIONS']['xm_goaccess']['json_path] = '/tmp/goaccess/goaccess.json';
