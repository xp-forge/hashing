<?php namespace text\hash;

use lang\Value;

/**
 * Base class
 *
 * @test  xp://text.hash.unittest.HashCodeTest
 */
abstract class HashCode implements Value {

  /**
   * Returns a HashCode instance from a hex string
   *
   * @param  string $hex
   * @return self
   */
  public static function fromHex($hex) { return new BytesHashCode(hex2bin($hex)); }

  /** @return int */
  public abstract function int();

  /** @return string */
  public abstract function hex();

  /** @return util.Bytes */
  public abstract function bytes();

  /** @return string */
  public abstract function string();

  /** @return string */
  public function toString() { return nameof($this).'('.$this->hex().')'; }

  /** @return string */
  public function hashCode() { return $this->hex(); }

  /**
   * Compare to another value
   *
   * @param  var $value
   * @return int
   */
  public function compareTo($value) {
    return $value instanceof self ? strcmp($this->hex(), $value->hex()) : 1;
  }

  /**
   * Check for equality to another HashCode. Uses constant time comparison
   *
   * @see    php://hash_equals
   * @param  string|text.hash.HashCode $value
   * @return bool
   */
  public function equals($value) {
    if ($value instanceof self) {
      return hash_equals($this->hex(), $value->hex());
    } else {
      return hash_equals($this->hex(), (string)$value);
    }
  }
}