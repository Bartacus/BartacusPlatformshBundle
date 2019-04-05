==============
Route Resolver
==============

The route resolver is currently the main and only functionality this bundle provides.

Usage
=====

The ``Bartacus\Bundle\PlatformshBundle\Route\RouteResolver`` is a handy utility to resolve a Platform.sh route you have defined to the real domain provided in the ``PLATFORM_ROUTES`` environment variable. This service can be autowired.

.. code-block:: php

    <?php

    class RedirectService
    {
        // ..

        public function someRedirectStuff()
        {
            // return e.g. www.develop-sr3snxi-projectid.eu-2.platform.site on develop environment
            $domain = $this->routeResolver->resolveDomain('www.{default}');

            // search for full URL, will return a RouteDefinition
            $route = $this->routeResolver->resolveRoute('https://www.{default}/');
        }
    }

Since one domain can exist as https and http route and either as upstream or redirect route the search order for ``resolveDomain`` is the following:

    * Upstream routes with HTTPS
    * Upstream routes with HTTP
    * Redirect routes with HTTPS
    * Redirect routes with HTTP

.. note::

    The ``RouteResolver`` doesn't work with the ``{all}`` placeholder in the moment. You can only resolve explicit routes.

TYPO3 domain records
====================

If you need to add a domain record to your TYPO3 installation, there is a new field called `Platform.sh Route Domain:` ``tx_bartacusplatformsh_routeDomainName]`` where you should add your configured route, e.g. ``www.{default}``.

The command ``vendor/bin/typo3cms domain:adapt`` will update it to the correct domain on your Platform.sh environment, execute it in the ``post_deploy`` hook.

Local development
=================

If a route is not resolvable, it throws a ``RouteNotFound`` or ``RouteDomainNotFound`` exception. Usually you don't have a ``PLATFORM_ROUTES`` environment variable available in your local development environment. To cope with that, you can add a ``.local_url`` key to your ``routes.yaml`` to simulate this.

.. code-block:: yaml

    # .platform/routes.yaml

    "https://www.{default}/": &upstream
        .local_url: "https://www.acme.local/"
        type: upstream
        upstream: "app:http"
        cache:
            enabled: false

.. note::

    The ``.local_url`` does only work on upstream routes.

Multi-app projects
------------------

If you have multi-app projects, your ``.platform/routes.yaml`` doesn't live in parallel to your ``.platform.app.yaml`` which is the default configuration to read in the local routes. You can change the path in the configuration, for example:

.. code-block:: yaml

    # config/packages/bartacus_platformsh.yaml
    bartacus_platformsh:
        platform_routes_path: '../.platform/routes.yaml'
