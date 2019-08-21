Hashing change log
==================

## ?.?.? / ????-??-??

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