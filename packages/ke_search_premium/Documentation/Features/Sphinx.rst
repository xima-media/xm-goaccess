.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. _Sphinx:

========================================
Sphinx
========================================

The support of Sphinx for the MySQL fulltext search can speed up ke_search queries.
This is useful particularly for large search indexes.

The perfomance depends on the server that is hosting ke_search.
On some machines Sphinx can be useful with 1.000 index entries. On other hardware
the performance is possably still quite good using the standard MySQL index with 10.000
index entries.

An additional benefit is the ability to make use of the "in-word-search" capabilities of sphinx (see below).

Restrictions
============
There are some restrictions to take into account if you want to use the Sphinx feature:

* starttime and endtime restrictons won't be applied. So make sure you do not need those restrictions in your search results.
* Not all of the other features work together with the spinx feature such as the :ref:`BoostKeywords`, :ref:`CustomRanking` and :ref:`DistanceSearch` features.
* Search words are always *and*-combined, not *or*.

Using Sphinx with ke_search
===========================
Please follow these steps to activate the Sphinx support for ke_search:

Install Sphinx
---------------
This feature is tested whith Sphinx 2.2.11. Please make sure to use that version. Newer versions of Sphinx 2 may work.
Sphinx 3 will most likely not work.

Please see the Sphinx documentation at
`http://sphinxsearch.com/docs/ <http://sphinxsearch.com/docs/>`_.

Sphinx can be downloaded here:
`http://sphinxsearch.com/downloads/ <http://sphinxsearch.com/downloads/>`_.

Building sphinx from source
^^^^^^^^^^^^^^^^^^^^^^^^^^^

Configure the install path with ``--prefix`` or Sphinx will be installed to ``/usr/local/``
what requires root permission on the machine.

Build sphinx:

::

  wget http://sphinxsearch.com/files/sphinx-2.2.11-release.tar.gz
  tar xzf sphinx-2.2.11-release.tar.gz
  cd sphinx-2.2.11-release/
  ./configure --prefix=/var/www/my_directory/sphinx
  make
  make install


Troubleshoting
..............

Try this if the error ``ERROR: cannot find MySQL include files.`` happens:

::

  sudo apt-get install libmysql++-dev

Example: Install necessary packages in ddev environment with MariaDB:

::

  sudo apt-get install build-essential libmariadb-dev-compat make

Try the following command if the error ``g++ not found`` occurs and start ``make install`` again:

::

	sudo apt-get install g++
	make install

For further issues try to get help
`here <http://sphinxsearch.com/wiki/doku.php?id=sphinx_on_debian_gnu_linux>`_

Sphinx setup
---------------

Sphinx is configured with a setup file.
This file has to be created as ``sphinx.conf`` in the Sphinx installation path in subfolder ``etc/``, e.g. ``/var/www/my_directory/sphinx/etc/sphinx.conf``.

Sphinx delivers an example config file ``sphinx.conf.dist`` that can be used as a reference.

Edit the file ``sphinx.conf`` and paste the following configuration. Don't forget to adjust database credentials and paths to fit your environment.

::

	source tx_kesearch_index {
		type = mysql
		sql_host = [MySQL-Server] In most cases „localhost“
		sql_user = [Username for MySQL]
		sql_pass = [Password for MySQL]
		sql_db = [Databasename for MySQL]
		sql_query_pre = SET NAMES utf8
		sql_query = SELECT uid, title, tags, content, sortdate, CONCAT('_pid_', pid) as pid, CONCAT('_group_', REPLACE(IF(fe_group="", "NULL", fe_group), ',', ',_group_')) as fe_group, CONCAT('_language_', language) AS language FROM tx_kesearch_index
		sql_field_string = title
		sql_field_string = language
		sql_field_string = fe_group
		sql_attr_timestamp = sortdate
	}
		index tx_kesearch_index {
		source = tx_kesearch_index
		path = /var/www/my_directory/sphinx/var/data/tx_kesearch_index
		charset_table = 0..9,A..Z->a..z,a..z,_,-,U+C4->U+E4,U+E4,U+D6->U+F6,U+F6,U+DC->U+FC,U+FC,U+DF,U+C0->U+E0,U+E0,U+C9->U+E9,U+E9
		min_word_len = 4
		min_prefix_len = 4
		min_infix_len = 0
	}
	indexer {
		mem_limit = 256M
	}
	searchd {
		listen = 9312
		log = /var/www/my_directory/sphinx/var/log/searchd.log
		pid_file = /var/www/my_directory/sphinx/var/log/searchd.pid
	}



Start Sphinx indexing
---------------------
After configuring Sphinx you can test to start the indexing process manually:

::

	/var/www/my_directory/sphinx/bin/indexer –all

In case of permission errors try these commands:

::

	chown [user]:[group] /var/www/my_directory/sphinx/etc/sphinx.conf
	chown -R [user]:[group] /var/www/my_directory/sphinx/var/data/


Start Sphinx server deamon
--------------------------

The Sphinx deamon can be started with ``searchd`` command, e.g.

::

	cd /var/www/my_directory/sphinx/bin/
	./searchd

*Hint*
 The deamon has to run permanently. Make sure that the deamon gets started automatically after a server reboot.


Configure TYPO3 extension
-------------------------

- Go to the extension settings of ``ke_search_premium`` in the TYPO3 backend.
- Activate the Sphinx support with setting ``enableSphinxSearch`` in chapter ``BasicSettings``
- Change to chapter ``Sphinx`` and configure the paths to the Sphinx binaries ``indexer`` and ``searchd``


Start ke_search indexer
-----------------------

After configuring of the extension you can start the ke_search indexer.
Go to backend module ``Faceted Search`` and choose function ``Start indexing``.
After that the search queries will be processed by Sphinx.


Enable "in word search" / partial word search
---------------------------------------------

Allows the user to search for part of a word which is inside another word (not only at the beginning), for example
this finds "tree" in "appletree". This works only with Sphinx.

You can enable this feature in the extension settings of ke_search_premium (Admin tools --> Settings).

You have to configure sphinx to allow search within words, change your sphinx.conf like this

::

  min_infix_len = 1
  min_prefix_len = 0

Restart the sphinx daemon and reindex.

You have to enable "partial word search" also for the ke_search main extension in the extension setting
(Admin tools --> Settings).


Sphinx error: sort-by attribute sortdate not found
--------------------------------------------------

Amend the Sphinx configuration in file ``sphinx.conf`` as follows if error ``sort-by attribute
'sortdate' not found`` occurs:

::

	source tx_kesearch_index {
		type = mysql
		sql_host = ...
		sql_user = ...
		sql_pass = ...
		sql_db = ...
		sql_query_pre = SET NAMES utf8
		sql_query = SELECT uid, title, tags, content, sortdate, CONCAT('_group_', REPLACE(IF(fe_group="", "NULL", fe_group), ',', ',_group_')) as fe_group, CONCAT('_language_', language) AS language FROM tx_kesearch_index
		sql_field_string = title
		sql_field_string = language
		sql_field_string = fe_group
		sql_attr_timestamp = sortdate
	}
