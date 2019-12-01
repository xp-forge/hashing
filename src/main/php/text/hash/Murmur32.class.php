<?php namespace text\hash;

use lang\IllegalStateException;

/**
 * MurmurHash3, 32-bit
 *
 * @see   https://en.wikipedia.org/wiki/MurmurHash
 * @test  xp://text.hash.unittest.MurmurTest
 */
class Murmur32 implements Hash {
  const C1 = 0xcc9e2d51;
  const C2 = 0x1b873593;

  private $hash, $values, $length;
  private $final= false;

  /**
   * Creates a new Murmur32 hash instance
   *
   * @param  int $seed
   */
  public function __construct($seed= 0) {
    $this->hash= $seed;
    $this->values= [];
    $this->length= 0;
  }

  /**
   * uint32 multiplication
   *
   * @param  int $l
   * @param  int $r
   * @return int
   * @see    https://stackoverflow.com/a/8536613
   */
  private static function mul32($l, $r) {
    return (((($l >> 16) * $r) << 16) + (($l & 0xffff) * $r)) & 0xffffffff;
  }

  /**
   * uint32 rotate left
   *
   * @param  int $x
   * @param  int $r
   * @return int
   */
  private static function rotl32($x, $r) {
    return ((($x << $r) & 0xffffffff) | (($x >> (32 - $r)) & 0xffffffff)) & 0xffffffff;
  }

  /**
   * Incrementally update the hash with a string.
   *
   * @param  string $string
   * @return self
   * @throws lang.IllegalStateException
   */
  public function update($string) {
    if ($this->final) {
      throw new IllegalStateException('Cannot reuse hash');
    }

    // Merge remainder with new byte values
    $h= $this->hash;
    $bytes= $this->values;
    foreach (unpack('C*', $string) as $byte) {
      $bytes[]= $byte;
    }

    // Consume as many 4-byte-chunks as possible
    for ($i= 0, $blocks= (int)(sizeof($bytes) / 4) * 4; $i < $blocks; $i+= 4) {
      $k= $bytes[$i] | ($bytes[$i + 1] << 8) | ($bytes[$i + 2] << 16) | ($bytes[$i + 3] << 24);
      $k= self::mul32($k, self::C1);
      $k= self::rotl32($k, 15);
      $k= self::mul32($k, self::C2);
      $h ^= $k;
      $h= self::rotl32($h, 13);
      $h= self::mul32($h, 5);
      $h= ($h + 0xe6546b64) & 0xffffffff;
    }

    // Store remainder
    $this->values= array_slice($bytes, $i);
    $this->length+= strlen($string);
    $this->hash= $h;

    return $this;
  }

  /**
   * Returns the final hash digest
   *
   * @param  string $string Optional string value
   * @return text.hash.HashCode
   * @throws lang.IllegalStateException
   */
  public function digest($string= null) {
    if ($this->final) {
      throw new IllegalStateException('Cannot reuse hash');
    }

    if (null !== $string) {
      $this->update($string);
    }

    $h= $this->hash;
    $k= 0;
    switch (sizeof($this->values)) {
      case 3: $k ^= $this->values[2] << 16;
      case 2: $k ^= $this->values[1] << 8;
      case 1: $k ^= $this->values[0];
        $k= self::mul32($k, self::C1);
        $k= self::rotl32($k, 15);
        $k= self::mul32($k, self::C2);
        $h ^= $k;
    }

    $h ^= $this->length;

    $h ^= ($h >> 16) & 0xffffffff;
    $h= self::mul32($h, 0x85ebca6b);
    $h ^= ($h >> 13) & 0xffffffff;
    $h = self::mul32($h, 0xc2b2ae35);
    $h ^= ($h >> 16) & 0xffffffff;

    $this->final= true;
    return new IntHashCode($h);
  }
}