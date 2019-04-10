=============
Config Reader
=============

This bundle requires the ``platformsh/config-reader`` (see https://github.com/platformsh/config-reader-php) and is usable for requiring your database, solr or redis configs.

Dependency Injection
====================

The ``Platformsh\ConfigReader\Config`` class is existent as public service, please use this service instead of initializing a new instance of the config reader every time you need it.

Credential Formatter
====================

Beside the default `credential formatters`_ provided by the config readers, this bundle provides the following additional config readers:

    * ``typo3_mysql`` provides the connection array to configure a TYPO3 database connection for MySQL or MariaDB.

.. _`credential formatters`: https://github.com/platformsh/config-reader-php#formatting-service-credentials
