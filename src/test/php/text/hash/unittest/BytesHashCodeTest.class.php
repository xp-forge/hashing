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

  #[@test]
  public function as_string() {
    $this->assertEquals('cnd0ue', (new BytesHashCode("\031\166\203\316"))->string());
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

  #[@test]
  public function string_representation() {
    $this->assertEquals('text.hash.BytesHashCode(7f0242ff)', (new BytesHashCode("\177\2\102\377"))->toString());
  }

  #[@test]
  public function hashcode_is_hex() {
    $this->assertEquals('7f0242ff', (new BytesHashCode("\177\2\102\377"))->hashCode());
  }

  #[@test]
  public function two_instances_with_same_hash_values_are_equal() {
    $this->assertEquals(new BytesHashCode("\0\0\0\0"), new BytesHashCode("\0\0\0\0"));
  }

  #[@test]
  public function two_instances_with_different_hash_values_are_not_equal() {
    $this->assertNotEquals(new BytesHashCode("\0\0\0\0"), new BytesHashCode("\0\0\0\1"));
  }

  #[@test]
  public function equality_using_equals() {
    $this->assertTrue(((new BytesHashCode("\0\0\0\0"))->equals(new BytesHashCode("\0\0\0\0"))));
  }

  #[@test]
  public function inequality_using_equals() {
    $this->assertFalse(((new BytesHashCode("\0\0\0\0"))->equals(new BytesHashCode("\0\0\0\1"))));
  }
}