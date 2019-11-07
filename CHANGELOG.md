# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [unreleased]

## [1.0.1] 2019-11-07

### Changed

- return type of `DataObject/Language::getChilds` not null
- license in readme file was wrongly stated as MIT
- version number in `metadata.php`

## [1.0.0] 2019-11-06

### Added

- Language queries, mutation, type and data access object
- `@Logged` and `@Right` annotations and updated tests

### Changed

- depends on `oxid-esales/graphql-base:^1.1`
- Namespace from `\OxidEsales\GraphQL` to `\OxidEsales\GraphQL\Translations`
- PSR2 -> PSR12
