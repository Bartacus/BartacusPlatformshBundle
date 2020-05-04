# Change Log
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/) 
and this project adheres to [Semantic Versioning](http://semver.org/).

## [Unreleased]
### Removed
- Command `domain:adapt`

## [2.1.0] - 2019-04-11
### Added
- New `typo3_mysql` credential formatter for easy formatting of the typo3 database connection
- Allow to register your own credential formatter with the DI tag `bartacus.platformsh.credential_formatter`
- Auto configure `bartacus.platformsh.credential_formatter` with interface `CredentialFormatterInterface`

### Breaking
- Updated to use `platformsh/config-reader` @ `^2.1`

## [2.0.1] - 2019-04-10
### Fixed
- Fix platform_routes_path default value to be resolved

## [2.0.0] - 2019-04-05
### Changed
- Support for TYPO3 9.5 only
- Minimal required Symfony version is 4.2

### Breaking
- The `platform_routes_path` config value must be an absolute path, e.g. `%kernel.project_dir%/../.platform/routes.yaml`

## [1.2.2] - 2019-03-08
### Fixed
- Fix the command name of the `domain:adapt` command

## [1.2.1] - 2019-02-11
### Fixed
- Compatibility with TYPO3 8.7.24 and upwards

## [1.2.0] - 2019-01-31
### Changed
- Add support for Symfony 4, drop support for Symfony 3
- Compatibility with helhum/typo3-console 5.6.0

## [1.1.8] - 2019-02-11
### Fixed
- Compatibility with TYPO3 8.7.24 and upwards

## [1.1.7] - 2018-12-02
### Fixed
- Compatibility with TYPO3 8.7.22

## [1.1.6] - 2018-11-14
### Fixed
- Compatibility with TYPO3 8.7.20

## [1.1.5] - 2018-06-23
### Fixed
- Compatibility with TYPO3 8.7.17

## [1.1.4] - 2018-06-13
### Fixed
- Compatibility with TYPO3 8.7.16

## [1.1.3] - 2018-05-24
### Fixed
- Compatibility with TYPO3 8.7.15

## [1.1.2] - 2018-03-08
### Fixed
- Compatibility with TYPO3 8.7.10

## [1.1.1] - 2018-01-24
### Fixed
- Compatibility with TYPO3 8.7.9

## [1.1.0] - 2017-09-21
### Added
- Add RouteResolver to resolve platform.sh routes into their environment URLs
- Resolve routes.yaml route key like domain into their environment domain
- Read local .platform/routes.yaml if no $PLATFORM_ROUTES is available (upstream routes only)
- Add a platform.sh route domain name to sys_domain records
- Add domain:adapt command to TYPO3_Console to adapt sys_domain records to the current environment

### Changed
- Install this bundle as TYPO3 extension

### Fixed
- Add TYPO3 override to extend sys_domain.domainName to 255 chars to allow longer platform.sh domains

## [1.0.2] - 2017-09-19
### Changed
- Change license to GPL-3.0+, because we include TYPO3 code in future

### Fixed
- Fix installation of unspecified version of TYPO3_Console which has patch conflicts

## [1.0.1] - 2017-09-08
### Fixed¶
- Compatibility with TYPO3 8.7.6

## [1.0.0] - 2017-09-05
### Added
- Create simply bundle with nothing in it yet
- Patch TYPO3 Console to remove install:generatepackagestates execution from install:setup command
- Patch TYPO3 CMS to not throw up on install:setup

[Unreleased]: https://github.com/Bartacus/BartacusPlatformshBundle/compare/2.1.0...HEAD
[2.1.0]: https://github.com/Bartacus/BartacusPlatformshBundle/compare/2.0.1...2.1.0
[2.0.1]: https://github.com/Bartacus/BartacusPlatformshBundle/compare/2.0.0...2.0.1
[2.0.0]: https://github.com/Bartacus/BartacusPlatformshBundle/compare/1.2.2...2.0.0
[1.2.2]: https://github.com/Bartacus/BartacusPlatformshBundle/compare/1.2.1...1.2.2
[1.2.1]: https://github.com/Bartacus/BartacusPlatformshBundle/compare/1.2.0...1.2.1
[1.2.0]: https://github.com/Bartacus/BartacusPlatformshBundle/compare/1.1.7...1.2.0
[1.1.8]: https://github.com/Bartacus/BartacusPlatformshBundle/compare/1.1.7...1.1.8
[1.1.7]: https://github.com/Bartacus/BartacusPlatformshBundle/compare/1.1.6...1.1.7
[1.1.6]: https://github.com/Bartacus/BartacusPlatformshBundle/compare/1.1.5...1.1.6
[1.1.5]: https://github.com/Bartacus/BartacusPlatformshBundle/compare/1.1.4...1.1.5
[1.1.4]: https://github.com/Bartacus/BartacusPlatformshBundle/compare/1.1.3...1.1.4
[1.1.3]: https://github.com/Bartacus/BartacusPlatformshBundle/compare/1.1.2...1.1.3
[1.1.2]: https://github.com/Bartacus/BartacusPlatformshBundle/compare/1.1.1...1.1.2
[1.1.1]: https://github.com/Bartacus/BartacusPlatformshBundle/compare/1.1.0...1.1.1
[1.1.0]: https://github.com/Bartacus/BartacusPlatformshBundle/compare/1.0.1...1.1.0
[1.0.2]: https://github.com/Bartacus/BartacusPlatformshBundle/compare/1.0.1...1.0.2
[1.0.1]: https://github.com/Bartacus/BartacusPlatformshBundle/compare/1.0.0...1.0.1
[1.0.0]: https://github.com/Bartacus/BartacusPlatformshBundle/compare/d84fd9f...1.0.0
