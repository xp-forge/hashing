<?php namespace text\hash;

use lang\IllegalArgumentException;
use lang\IllegalStateException;
use util\{Bytes, Secret};

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
   * @param  string|util.Bytes|util.Secret $arg
   * @return self
   * @throws lang.IllegalStateException
   */
  public function update($arg) {
    if (null === $this->hash) {
      throw new IllegalStateException('Cannot reuse hash');
    }

    if ($arg instanceof Secret) {
      hash_update($this->hash, $arg->reveal());
    } else if ($arg instanceof Bytes) {
      hash_update($this->hash, (string)$arg);
    } else {
      hash_update($this->hash, $arg);
    }

    return $this;
  }

  /**
   * Returns the final hash digest
   *
   * @param  ?string|util.Bytes|util.Secret $arg Optional string value
   * @return text.hash.HashCode
   * @throws lang.IllegalStateException
   */
  public function digest($arg= null) {
    if (null === $this->hash) {
      throw new IllegalStateException('Cannot reuse hash');
    }

    if (null !== $arg) {
      $this->update($arg);
    }

    try {
      return new BytesHashCode(hash_final($this->hash, true));
    } finally {
      $this->hash= null;
    }
  }
}