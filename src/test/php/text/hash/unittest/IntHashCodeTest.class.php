<?php namespace text\hash\unittest;

use text\hash\IntHashCode;
use test\Assert;
use test\{Test, TestCase, Values};
use util\Bytes;

class IntHashCodeTest {

  #[Test]
  public function can_create() {
    new IntHashCode(0);
  }

  #[Test]
  public function as_int() {
    Assert::equals(0, (new IntHashCode(0))->int());
  }

  #[Test]
  public function as_base32() {
    Assert::equals('cnd0ue', (new IntHashCode(427197390))->base32());
  }

  #[Test]
  public function string_cast() {
    Assert::equals('197683ce', (string)new IntHashCode(427197390));
  }

  #[Test, Values([[0, '00000000'], [1, '00000001'], [2130854655, '7f0242ff'],])]
  public function as_hex($hash, $expected) {
    Assert::equals($expected, (new IntHashCode($hash))->hex());
  }

  #[Test, Values([[0, "\0\0\0\0"], [1, "\0\0\0\1"], [2130854655, "\177\2\102\377"],])]
  public function as_bytes($hash, $expected) {
    Assert::equals(new Bytes($expected), (new IntHashCode($hash))->bytes());
  }

  #[Test]
  public function crc32() {
    Assert::equals(hash('crc32b', 'Test'), (new IntHashCode(crc32('Test')))->hex());
  }

  #[Test]
  public function string_representation() {
    Assert::equals('text.hash.IntHashCode(7f0242ff)', (new IntHashCode(2130854655))->toString());
  }

  #[Test]
  public function hashcode_is_hex() {
    Assert::equals('7f0242ff', (new IntHashCode(2130854655))->hashCode());
  }
}