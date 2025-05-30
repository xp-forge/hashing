Hashing
=======

[![Build status on GitHub](https://github.com/xp-forge/hashing/workflows/Tests/badge.svg)](https://github.com/xp-forge/hashing/actions)
[![XP Framework Module](https://raw.githubusercontent.com/xp-framework/web/master/static/xp-framework-badge.png)](https://github.com/xp-framework/core)
[![BSD Licence](https://raw.githubusercontent.com/xp-framework/web/master/static/licence-bsd.png)](https://github.com/xp-framework/core/blob/master/LICENCE.md)
[![Requires PHP 7.4+](https://raw.githubusercontent.com/xp-framework/web/master/static/php-7_4plus.svg)](http://php.net/)
[![Supports PHP 8.0+](https://raw.githubusercontent.com/xp-framework/web/master/static/php-8_0plus.svg)](http://php.net/)
[![Latest Stable Version](https://poser.pugx.org/xp-forge/hashing/version.svg)](https://packagist.org/packages/xp-forge/hashing)

Fluent interface to hashing functions provided by PHP, extended with Murmur3.

Examples
--------

Calculate hash for a string, output using base32:

```php
use text\hash\Hashing;

$hash= Hashing::murmur3_32()->new($seed= 0x2a);
$base32= $hash->digest('The quick brown fox jumps over the lazy dog.')->base32();
```

Incrementally updating hash, output hex (much like the builtin `md5()` function does):

```php
use text\hash\Hashing;

$hash= Hashing::md5()->new();
while ($stream->available()) {
  $hash->update($stream->read());
}

$hex= $hash->digest()->hex();
```

Comparing hashes using constant time comparison:

```php
use text\hash\{Hashing, HashCode};

$computed= Hashing::sha256()->digest($req->param('password')); // From request
$stored= HashCode::fromHex($record['password_hash']);          // From database

if ($computed->equals($stored)) {
  // Not susceptible to timing attacks
}
```

Algorithms
----------
The following algorithms exist as shortcuts inside the entry point class:

* `Hashing::md5()`
* `Hashing::sha1()`
* `Hashing::sha256()`
* `Hashing::sha512()`
* `Hashing::murmur3_32()`
* `Hashing::murmur3_128()`

[Other algorithms](https://www.php.net/manual/en/function.hash-algos.php) can be instantiated via `Hashing::algorithm(string $name, var... $args)`, which may raise an *IllegalArgumentException* if the given algorithm is not available.