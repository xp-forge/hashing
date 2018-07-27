<?php namespace text\hash;

use util\Bytes;

/**
 * A hashcode created from a 32-bit int
 *
 * @see   php://pack
 * @test  xp://text.hash.unittest.IntHashCodeTest
 */
class IntHashCode extends HashCode {
  private $int;

  /** @param int $int */
  public function __construct($int) {
    $this->int= $int;
  }

  /** @return int */
  public function int() { return $this->int; }

  /** @return string */
  public function hex() { return sprintf('%08x', $this->int); }

  /** @return util.Bytes */
  public function bytes() { return new Bytes(pack('N', $this->int)); }

  /** @return string */
  public function string() { return base_convert($this->int, 10, 32); }
}