<?php namespace text\hash;

use io\streams\InputStream;

/**
 * An input stream that computes a hash code of the data read from it
 *
 * @test  text.hash.unittest.HashingInputStreamTest
 */
class HashingInputStream implements InputStream {
  private $hash, $in;

  /**
   * Creates a new instance
   *
   * @param  text.hash.Hash|text.hash.Algorithm|string $arg
   * @param  io.streams.InputStream $in
   */
  public function __construct($arg, InputStream $in) {
    if ($arg instanceof Hash) {
      $this->hash= $arg;
    } else if ($arg instanceof Algorithm) {
      $this->hash= $arg->new();
    } else {
      $this->hash= Hashing::algorithm((string)$arg)->new();
    }
    $this->in= $in;
  }

  /** @return int */
  public function available() {
    return $this->in->available();
  }

  /**
   * Read a string
   *
   * @param  int $limit
   * @return string
   */
  public function read($limit= 8192) {
    $bytes= $this->in->read($limit);
    $this->hash->update($bytes);
    return $bytes;
  }

  /** @return void */
  public function close() {
    $this->in->close();
  }

  /** Returns the hash code */
  public function digest(): HashCode {
    return $this->hash->digest();
  }
}