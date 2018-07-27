<?php namespace text\hash\unittest;

use text\hash\BytesHashCode;
use text\hash\HashCode;
use text\hash\IntHashCode;
use unittest\TestCase;

class HashCodeTest extends TestCase {

  #[@test]
  public function from_hex() {
    $hex= '640ab2bae07bedc4c163f679a746f7ab7fb5d1fa';
    $this->assertEquals($hex, HashCode::fromHex($hex)->hex());
  }

  #[@test]
  public function equality() {
    $this->assertTrue(HashCode::fromHex('7f0242ff')->equals(new BytesHashCode("\177\2\102\377")));
  }

  #[@test]
  public function inequality() {
    $this->assertFalse(HashCode::fromHex('7f0242ff')->equals(new IntHashCode(0)));
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