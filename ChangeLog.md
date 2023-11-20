Hashing change log
==================

## ?.?.? / ????-??-??

* Added PHP 8.4 to the test matrix - @thekid
* Migrated PR #4: Migrate to new testing library - @thekid

## 2.1.0 / 2022-09-24

* Use native `murmur3a` for *murmur3_32* algorithm as of PHP 8.1.0, see
  Changelog at https://www.php.net/manual/de/function.hash-algos.php
  (@thekid)
* Merged PR #3: Add support for passing `util.Bytes` and `util.Secret`
  instances.
  (@thekid)
* Removed PHP 5 compatibility left-overs, resulting in a tiny performance
  increase due to removed conditionally loaded files.
  (@thekid)

## 2.0.4 / 2022-02-26

* Fixed "Creation of dynamic property" warnings in PHP 8.2 - @thekid

## 2.0.3 / 2020-10-21

* Made library compatible with XP 11 - @thekid

## 2.0.2 / 2020-10-04

* Fixed "Array and string offset access syntax with curly braces is no
  longer supported" warnings
  (@thekid)

## 2.0.1 / 2020-04-05

* Implemented RFC #335: Remove deprecated key/value pair annotation syntax
  (@thekid)

## 2.0.0 / 2019-12-01

* Implemented xp-framework/rfc#334: Drop PHP 5.6. The minimum required
  PHP version is now 7.0.0!
  (@thekid)

## 1.0.1 / 2019-12-01

* Made compatible with XP 10 - @thekid

## 1.0.0 / 2019-08-21

* Ensured hashes cannot be reused after `digest()` has been called,
  preventing *supplied resource is not a valid Hash Context resource*
  warnings for the native implementation and unpredictable results.
  (@thekid)
* Fixed compatibility with PHP 7.4 - @thekid

## 0.2.0 / 2019-01-14

* Made HashCode instances castable to string - @thekid
* **Heads up:** Renamed HashCode's string method to `base32` - @thekid
* Overloaded HashCode::equals() to also accept hex strings - @thekid
* Merged PR #2: Add digest() shortcut to Algorithm - @thekid

## 0.1.0 / 2018-08-12

* Merged PR #1: Add Murmur3 128-bit hashing algorithm - @thekid
* Hello World! First release - @thekid