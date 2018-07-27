<?php namespace text\hash;

use util\Bytes;

class BytesHashCode implements HashCode {
  private $bytes;

  /** @param int $bytes */
  public function __construct($bytes) {
    $this->bytes= $bytes;
  }

  /** @return int */
  public function int() {
    return (
      (ord($this->bytes{0}) & 0xff) << 24 |
      (ord($this->bytes{1}) & 0xff) << 16 |
      (ord($this->bytes{2}) & 0xff) << 8 |
      ord($this->bytes{3}) & 0xff
    );
  }

  /** @return string */
  public function hex() { return bin2hex($this->bytes); }

  /** @return util.Bytes */
  public function bytes() { return new Bytes($this->bytes); }
}