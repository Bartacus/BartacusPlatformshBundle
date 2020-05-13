.. _getting-started-configuration:

=============
Configuration
=============

The bundle itself doesn't need any configuration. To get your project up and running you need to add some configuration for Platform.sh. For the full reference of the configuration see https://docs.platform.sh

Your App Configuration
======================

A simple starting configuration for your app. Put it as ``.platform.app.yaml`` into your project root.

.. code-block:: yaml

    # The name of this app. Must be unique within a project.
    name: app

    # The type of the application to build.
    type: php:7.2
    build:
        flavor: composer

    variables:
        env:
            # Tell Symfony/TYPO3 to always install in production-mode.
            APP_ENV: 'prod'
            APP_DEBUG: 0
            TYPO3_CONTEXT: 'Production'

    runtime:
        extensions:
            - apcu
            - imagick
        sizing_hints:
            request_memory: 10

    # The relationships of the application with services or other applications.
    # The left-hand side is the name of the relationship as it will be exposed
    # to the application in the PLATFORM_RELATIONSHIPS variable. The right-hand
    # side is in the form `:`.
    relationships:
        database: 'mysqldb:mysql'

    # The size of the persistent disk of the application (in MB).
    disk: 2048

    # The mounts that will be performed when the package is deployed.
    mounts:
        'var':
            source: local
            source_path: var
        'public/fileadmin':
            source: local
            source_path: fileadmin
        'public/typo3temp':
            source: local
            source_path: typo3temp

    # The configuration of app when it is exposed to the web.
    web:
        locations:
            '/':
                # The public directory of the app, relative to its root.
                root: 'public'
                # The front-controller script to send non-static requests to.
                passthru: '/index.php'
                index:
                    - 'index.php'
                allow: false
                rules:
                    # Allow access to common static files.
                    '\.(jpe?g|png|gif|svgz?|css|js|map|ico|bmp|eot|woff2?|otf|ttf)$':
                        expires: 7d
                        allow: true
                    '^/robots\.txt$':
                        allow: true
                    '^/sitemap\.xml$':
                        allow: true
                    '^/manifest\.json$':
                        allow: true
            '/fileadmin':
                root: 'public/fileadmin'
                scripts: false
                allow: true
                passthru: '/index.php'
            '/fileadmin/_processed_':
                root: 'public/fileadmin/_processed_'
                expires: 7d
                scripts: false
                allow: true
                passthru: false
            '/typo3temp/assets':
                root: 'public/typo3temp/assets'
                expires: 7d
                scripts: false
                allow: true
                passthru: false
                rules:
                    '\.js\.gzip$':
                        headers:
                            Content-Type: text/javascript
                            Content-Encoding: gzip
                    '\.css\.gzip$':
                        headers:
                            Content-Type: text/css
                            Content-Encoding: gzip
            '/typo3conf/LocalConfiguration.php':
                allow: false
            '/typo3conf/AdditionalConfiguration.php':
                allow: false

    # The hooks that will be performed when the package is deployed.
    hooks:
        build: |
            cd public/typo3conf/
            ln -s writeable/ENABLE_INSTALL_TOOL ENABLE_INSTALL_TOOL
        deploy: |
            set -x

            bin/console cache:clear --no-warmup || rm -rf var/cache/*
            bin/console cache:warmup
            vendor/bin/typo3cms cache:flush

            touch public/typo3conf/ENABLE_INSTALL_TOOL
            vendor/bin/typo3cms database:updateschema safe
        post_deploy: |
            set -x

            vendor/bin/typo3cms install:fixfolderstructure

            vendor/bin/typo3cms upgrade:all

    crons:
        typo3:
            spec: '*/5 * * * *'
            cmd: 'vendor/bin/typo3cms scheduler:run'

.. warning::

    The first push on Platform.sh will fail, because there is no installation happening. You need to do the TYPO3 installation locally and then sync up the database to Platform.sh and subsequent pushes should work.

Route additions
===============

If you want to use the :ref:`route-resolver` in local development too, extend your ``.platform/routes.yaml`` like shown here:

.. code-block:: yaml

    "https://www.{default}/": &upstream
        .local_url: "https://www.acme.local/"
        type: upstream
        upstream: "app:http"
        cache:
            enabled: false
