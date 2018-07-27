<?php namespace text\hash;

abstract class Hashing {

  /**
   * Returns the `md5` hash function. For security, use `sha256` or higher!
   *
   * @return text.hash.Hash
   */
  public static function md5() { return new Native('md5'); }

  /**
   * Returns the `sha1` hash function. For security, use `sha256` or higher!
   *
   * @return text.hash.Hash
   */
  public static function sha1() { return new Native('sha1'); }

  /**
   * Returns the `sha256` hash function.
   *
   * @return text.hash.Hash
   */
  public static function sha256() { return new Native('sha256'); }

  /**
   * Returns the `sha512` hash function.
   *
   * @return text.hash.Hash
   */
  public static function sha512() { return new Native('sha512'); }

  /**
   * Returns the `murmur3` 32-bit hash function (C++ equivalent: MurmurHash3_x86_32)
   *
   * @return text.hash.Hash
   */
  public static function murmur3_32($seed= 0) { return new Murmur32($seed); }

  /**
   * Returns a list of supported algorithm names
   *
   * @see    php://hash_algos
   * @return string[]
   */
  public static function algorithms() {
    return array_merge(hash_algos(), ['murmur3_32']);
  }

  /**
   * Uses an algorithm specified by its name to compute a hash
   *
   * @param  string $algorithm
   * @param  var... $args
   * @return text.hash.Hash
   * @throws lang.IllegalArgumentException If the hashing algorithm is unknown
   */
  public static function algorithm($algorithm, ... $args) {
    switch ($algorithm) {
      case 'murmur3_32': return new Murmur32(...$args);
      default: return new Native($algorithm);
    }
  }

}