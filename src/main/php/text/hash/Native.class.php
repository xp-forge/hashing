<?php namespace text\hash;

use lang\IllegalArgumentException;

class Native implements Hash {

  /**
   * Creates a new native hashing function
   *
   * @see    php://hash_init
   * @param  string $algorithm
   * @throws lang.IllegalArgumentException
   */
  public function __construct($algorithm) {
    if (!($this->hash= hash_init($algorithm))) {
      $e= new IllegalArgumentException('No such algorithm "'.$algorithm.'"');
      \xp::gc(__FILE__);
      throw $e;
    }
  }

  /**
   * Incrementally update the hash with a string.
   *
   * @param  string $string
   * @return self
   */
  public function update($string) {
    hash_update($this->hash, $string);
    return $this;
  }

  /**
   * Returns the final hash digest
   *
   * @return text.hash.HashCode
   */
  public function digest() {
    return new BytesHashCode(hash_final($this->hash, true));
  }
}