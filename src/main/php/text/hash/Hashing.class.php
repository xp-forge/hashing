<?php namespace text\hash;

abstract class Hashing {

  /**
   * Returns the `md5` hash function. For security, use `sha256` or higher!
   *
   * @return text.hash.Algorithm
   */
  public static function md5() { return new Algorithm(function(... $args) { return new Native('md5'); }); }

  /**
   * Returns the `sha1` hash function. For security, use `sha256` or higher!
   *
   * @return text.hash.Algorithm
   */
  public static function sha1() { return new Algorithm(function(... $args) { return new Native('sha1'); }); }

  /**
   * Returns the `sha256` hash function.
   *
   * @return text.hash.Algorithm
   */
  public static function sha256() { return new Algorithm(function(... $args) { return new Native('sha256'); }); }

  /**
   * Returns the `sha512` hash function.
   *
   * @return text.hash.Algorithm
   */
  public static function sha512() { return new Algorithm(function(... $args) { return new Native('sha512'); }); }

  /**
   * Returns the `murmur3` 32-bit hash function (C++ equivalent: MurmurHash3_x86_32)
   *
   * @return text.hash.Algorithm
   */
  public static function murmur3_32() {
    return PHP_VERSION_ID >= 80100
      ? new Algorithm(function(... $args) { return new Native('murmur3a'); })
      : new Algorithm(function(... $args) { return new Murmur32(...$args); })
    ;
  }

  /**
   * Returns the `murmur3` 128-bit hash function (C++ equivalent: MurmurHash3_x64_128)
   *
   * @return text.hash.Algorithm
   */
  public static function murmur3_128() { return new Algorithm(function(... $args) { return new Murmur128(...$args); }); }

  /**
   * Returns a list of supported algorithm names
   *
   * @see    php://hash_algos
   * @return [:text.hash.Algorithm]
   */
  public static function algorithms() {
    $r= [
      'murmur3_32'  => self::murmur3_32(),
      'murmur3_128' => self::murmur3_128(),
    ];

    // Add all algorithms natively built in to PHP
    foreach (hash_algos() as $algorithm) {
      $r[$algorithm]= new Algorithm(function(... $args) use($algorithm) { return new Native($algorithm); });
    }
    return $r;
  }

  /**
   * Uses an algorithm specified by its name to compute a hash
   *
   * @param  string $algorithm
   * @param  var... $args
   * @return text.hash.Algorithm
   * @throws lang.IllegalArgumentException If the hashing algorithm is unknown
   */
  public static function algorithm($algorithm, ... $args) {
    switch ($algorithm) {
      case 'murmur3_32': return new Algorithm(function(... $args) { return new Murmur32(...$args); });
      case 'murmur3_128': return new Algorithm(function(... $args) { return new Murmur128(...$args); });
      default: return new Algorithm(function(... $args) use($algorithm) { return new Native($algorithm); });
    }
  }
}