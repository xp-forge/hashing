<?php namespace text\hash;

use lang\Value;

abstract class HashCode implements Value {

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
   * @param  text.hash.HashCode $value
   * @return bool
   */
  public function equals(self $value) {
    return hash_equals($this->hex(), $value->hex());
  }
}