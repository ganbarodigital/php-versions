# CHANGELOG

## develop branch

### New

* Reusable dataset for testing hashed versions
* Exceptions\E4xx_UnsupportedOperation - added
* HashedVersions\Operations\CompareHashedVersions - added
* HashedVersions\Parsers\ParseHashedVersion - added
* HashedVersions\Values\HashedVersion - added

### Fixes

* E4xx_UnsupportedType - uses the latest UnsupportedType trait from the Exceptions package

## 1.0.0 - Sat Oct 24 2015

### New

* Initial support for generic version numbers
* Initial support for Semantic Version numbers
* Initial support for version range expressions

## 0.1.0 - Sun Jun 28 2015

This started life as stuart/php-semver. I'm in the process of overhauling it and making it much more flexible.

### New

* SemanticVersion numbers fully supported