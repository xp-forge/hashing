<?php namespace text\hash\unittest;

use text\hash\IntHashCode;
use unittest\TestCase;
use util\Bytes;

class IntHashCodeTest extends TestCase {

  #[@test]
  public function can_create() {
    new IntHashCode(0);
  }

  #[@test]
  public function as_int() {
    $this->assertEquals(0, (new IntHashCode(0))->int());
  }

  #[@test]
  public function as_string() {
    $this->assertEquals('cnd0ue', (new IntHashCode(427197390))->string());
  }

  #[@test, @values([
  #  [0, '00000000'],
  #  [1, '00000001'],
  #  [2130854655, '7f0242ff'],
  #])]
  public function as_hex($hash, $expected) {
    $this->assertEquals($expected, (new IntHashCode($hash))->hex());
  }

  #[@test, @values([
  #  [0, "\0\0\0\0"],
  #  [1, "\0\0\0\1"],
  #  [2130854655, "\177\2\102\377"],
  #])]
  public function as_bytes($hash, $expected) {
    $this->assertEquals(new Bytes($expected), (new IntHashCode($hash))->bytes());
  }

  #[@test]
  public function crc32() {
    $this->assertEquals(hash('crc32b', 'Test'), (new IntHashCode(crc32('Test')))->hex());
  }

  #[@test]
  public function two_instances_with_same_hash_values_are_equal() {
    $this->assertEquals(new IntHashCode(0), new IntHashCode(0));
  }

  #[@test]
  public function two_instances_with_different_hash_values_are_not_equal() {
    $this->assertNotEquals(new IntHashCode(0), new IntHashCode(1));
  }
}