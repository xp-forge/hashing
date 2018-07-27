<?php namespace text\hash\unittest;

use text\hash\BytesHashCode;
use unittest\TestCase;
use util\Bytes;

class BytesHashCodeTest extends TestCase {

  #[@test]
  public function can_create() {
    new BytesHashCode("\177\2\102\377");
  }

  #[@test]
  public function as_bytes() {
    $this->assertEquals(new Bytes("\0\0\0\0"), (new BytesHashCode("\0\0\0\0"))->bytes());
  }

  #[@test, @values([
  #  ["\0\0\0\0", 0],
  #  ["\0\0\0\1", 1],
  #  ["\177\2\102\377", 2130854655],
  #])]
  public function as_int($hash, $expected) {
    $this->assertEquals($expected, (new BytesHashCode($hash))->int());
  }

  #[@test, @values([
  #  ["\0\0\0\0", '00000000'],
  #  ["\0\0\0\1", '00000001'],
  #  ["\177\2\102\377", '7f0242ff'],
  #])]
  public function as_hex($hash, $expected) {
    $this->assertEquals($expected, (new BytesHashCode($hash))->hex());
  }

  #[@test]
  public function md5() {
    $this->assertEquals(md5('Test'), (new BytesHashCode(md5('Test', true)))->hex());
  }
}