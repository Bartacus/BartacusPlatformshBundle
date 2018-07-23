# Change Log
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/) 
and this project adheres to [Semantic Versioning](http://semver.org/).

## [Unreleased]

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
### Fixed
- Compatibility with TYPO3 8.7.6

## [1.0.0] - 2017-09-05
### Added
- Create simply bundle with nothing in it yet
- Patch TYPO3 Console to remove install:generatepackagestates execution from install:setup command
- Patch TYPO3 CMS to not throw up on install:setup

[Unreleased]: https://github.com/Bartacus/BartacusPlatformshBundle/compare/1.1.5...HEAD
[1.1.5]: https://github.com/Bartacus/BartacusPlatformshBundle/compare/1.1.4...1.1.5
[1.1.4]: https://github.com/Bartacus/BartacusPlatformshBundle/compare/1.1.3...1.1.4
[1.1.3]: https://github.com/Bartacus/BartacusPlatformshBundle/compare/1.1.2...1.1.3
[1.1.2]: https://github.com/Bartacus/BartacusPlatformshBundle/compare/1.1.1...1.1.2
[1.1.1]: https://github.com/Bartacus/BartacusPlatformshBundle/compare/1.1.0...1.1.1
[1.1.0]: https://github.com/Bartacus/BartacusPlatformshBundle/compare/1.0.1...1.1.0
[1.0.2]: https://github.com/Bartacus/BartacusPlatformshBundle/compare/1.0.1...1.0.2
[1.0.1]: https://github.com/Bartacus/BartacusPlatformshBundle/compare/1.0.0...1.0.1
[1.0.0]: https://github.com/Bartacus/BartacusPlatformshBundle/compare/d84fd9f...1.0.0
