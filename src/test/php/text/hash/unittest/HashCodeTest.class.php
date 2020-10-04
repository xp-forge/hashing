<?php namespace text\hash\unittest;

use text\hash\{BytesHashCode, HashCode, IntHashCode};
use unittest\{Test, TestCase, Values};

class HashCodeTest extends TestCase {

  /** @return iterable */
  private function comparison() {
    yield [new IntHashCode(6100), new IntHashCode(6100)];
    yield [new BytesHashCode("\0\377\1\6"), new BytesHashCode("\0\377\1\6")];
  }

  #[Test]
  public function from_hex() {
    $hex= '640ab2bae07bedc4c163f679a746f7ab7fb5d1fa';
    $this->assertEquals($hex, HashCode::fromHex($hex)->hex());
  }

  #[Test]
  public function equality() {
    $this->assertTrue(HashCode::fromHex('7f0242ff')->equals(new BytesHashCode("\177\2\102\377")));
  }

  #[Test]
  public function inequality() {
    $this->assertFalse(HashCode::fromHex('7f0242ff')->equals(new IntHashCode(0)));
  }

  #[Test]
  public function equality_with_string() {
    $this->assertTrue(HashCode::fromHex('7f0242ff')->equals('7f0242ff'));
  }

  #[Test]
  public function inequality_with_string() {
    $this->assertFalse(HashCode::fromHex('7f0242ff')->equals(''));
  }

  #[Test, Values('comparison')]
  public function two_instances_with_same_hash_values_are_equal($a, $b) {
    $this->assertEquals($a, $b);
  }

  #[Test]
  public function two_instances_with_different_hash_values_are_not_equal() {
    $this->assertNotEquals(new IntHashCode(0), new IntHashCode(1));
  }
}