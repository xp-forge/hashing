<?php namespace text\hash\unittest;

use text\hash\BytesHashCode;
use test\Assert;
use test\{Test, TestCase, Values};
use util\Bytes;

class BytesHashCodeTest {

  #[Test]
  public function can_create() {
    new BytesHashCode("\177\2\102\377");
  }

  #[Test]
  public function as_bytes() {
    Assert::equals(new Bytes("\0\0\0\0"), (new BytesHashCode("\0\0\0\0"))->bytes());
  }

  #[Test]
  public function as_base32() {
    Assert::equals('cnd0ue', (new BytesHashCode("\031\166\203\316"))->base32());
  }

  #[Test]
  public function string_cast() {
    Assert::equals('197683ce', (string)new BytesHashCode("\031\166\203\316"));
  }


  #[Test, Values([["\0\0\0\0", 0], ["\0\0\0\1", 1], ["\177\2\102\377", 2130854655],])]
  public function as_int($hash, $expected) {
    Assert::equals($expected, (new BytesHashCode($hash))->int());
  }

  #[Test, Values([["\0\0\0\0", '00000000'], ["\0\0\0\1", '00000001'], ["\177\2\102\377", '7f0242ff'],])]
  public function as_hex($hash, $expected) {
    Assert::equals($expected, (new BytesHashCode($hash))->hex());
  }

  #[Test]
  public function md5() {
    Assert::equals(md5('Test'), (new BytesHashCode(md5('Test', true)))->hex());
  }

  #[Test]
  public function string_representation() {
    Assert::equals('text.hash.BytesHashCode(7f0242ff)', (new BytesHashCode("\177\2\102\377"))->toString());
  }

  #[Test]
  public function hashcode_is_hex() {
    Assert::equals('7f0242ff', (new BytesHashCode("\177\2\102\377"))->hashCode());
  }
}