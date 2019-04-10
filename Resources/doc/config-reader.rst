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

Create your own credential formatter
------------------------------------

You can add your own credential formatters to the config reader by implementing the ``CredentialFormatterInterface`` and returning an array of formatter name as key and the method name as value in the static ``getFormatters()`` method. Example:

.. code-block:: php

    <?php

    namespace App/CredentialFormatter

    class SolrCredentialFormatter implements CredentialFormatterInterface
    {
        public static function getFormatters(): array
        {
            return [
                'typo3_solr' => 'formatSolrConnection',
            ];
        }

        public function formatSolrConnection(array $credentials): array
        {
            return [
                // ...
            ]
        }
    }

Tag your credential formatter service with ``bartacus.platformsh.credential_formatter`` and it's registered. Example:

.. code-block:: yaml

    services:
        App/CredentialFormatter/SolrCredentialFormatter:
            tags: ['bartacus.platformsh.credential_formatter']

.. _`credential formatters`: https://github.com/platformsh/config-reader-php#formatting-service-credentials
