<?php namespace text\hash;

abstract class Hashing {

  public static function md5() { return new Native('md5'); }

  public static function sha1() { return new Native('sha1'); }

  public static function sha256() { return new Native('sha256'); }

  public static function sha512() { return new Native('sha512'); }

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