=============
Config Reader
=============

This bundle requires the ``platformsh/config-reader`` (see https://github.com/platformsh/config-reader-php) and is usable for requiring your database, solr or redis configs.

Dependency Injection
====================

The ``Platformsh\ConfigReader\Config`` class is existent as public service, please use this service instead of initializing a new instance of the config reader every time you need it.
