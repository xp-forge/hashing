<?php namespace text\hash;

use lang\IllegalArgumentException;
use lang\IllegalStateException;

class Native implements Hash {
  private $hash;

  /**
   * Creates a new native hashing function
   *
   * @see    php://hash_init
   * @param  string $algorithm
   * @throws lang.IllegalArgumentException
   */
  public function __construct($algorithm) {
    try {
      if (!($this->hash= hash_init($algorithm))) {
        $e= new IllegalArgumentException('No such algorithm "'.$algorithm.'"');
        \xp::gc(__FILE__);
        throw $e;
      }
    } catch (\Error $e) {
      throw new IllegalArgumentException('No such algorithm "'.$algorithm.'"', $e);
    }
  }

  /**
   * Incrementally update the hash with a string.
   *
   * @param  string $string
   * @return self
   * @throws lang.IllegalStateException
   */
  public function update($string) {
    if (null === $this->hash) {
      throw new IllegalStateException('Cannot reuse hash');
    }
    hash_update($this->hash, $string);
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
    if (null === $this->hash) {
      throw new IllegalStateException('Cannot reuse hash');
    }

    if (null !== $string) {
      hash_update($this->hash, $string);
    }

    try {
      return new BytesHashCode(hash_final($this->hash, true));
    } finally {
      $this->hash= null;
    }
  }
}