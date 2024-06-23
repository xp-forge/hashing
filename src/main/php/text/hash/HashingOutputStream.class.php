<?php namespace text\hash;

use io\streams\OutputStream;

/**
 * An output stream that computes a hash code of the data written to it
 *
 * @test  text.hash.unittest.HashingOutputStreamTest
 */
class HashingOutputStream implements OutputStream {
  private $hash, $out;

  /**
   * Creates a new instance
   *
   * @param  text.hash.Hash|text.hash.Algorithm|string $arg
   * @param  io.streams.OutputStream $out
   */
  public function __construct($arg, OutputStream $out) {
    if ($arg instanceof Hash) {
      $this->hash= $arg;
    } else if ($arg instanceof Algorithm) {
      $this->hash= $arg->new();
    } else {
      $this->hash= Hashing::algorithm((string)$arg)->new();
    }
    $this->out= $out;
  }

  /**
   * Write a string
   *
   * @param  var $arg
   * @return void
   */
  public function write($bytes) {
    $this->out->write($bytes);
    $this->hash->update($bytes);
  }

  /** @return void */
  public function flush() {
    $this->out->flush();
  }

  /** @return void */
  public function close() {
    $this->out->close();
  }

  /** Returns the hash code */
  public function digest(): HashCode {
    return $this->hash->digest();
  }
}