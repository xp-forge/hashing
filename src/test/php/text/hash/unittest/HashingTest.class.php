<?php namespace text\hash\unittest;

use lang\{IllegalArgumentException, IllegalStateException};
use test\Assert;
use test\{Expect, Test, TestCase, Values};
use text\hash\Hashing;
use util\{Bytes, Secret};

class HashingTest {

  #[Test, Values([['', 'd41d8cd98f00b204e9800998ecf8427e'], ['Test', '0cbc6611f5540bd0809a388dc95a615b']])]
  public function md5($value) {
    Assert::equals(md5($value), Hashing::md5()->new()->digest($value)->hex());
  }

  #[Test, Values([['', 'da39a3ee5e6b4b0d3255bfef95601890afd80709'], ['Test', '640ab2bae07bedc4c163f679a746f7ab7fb5d1fa']])]
  public function sha1($value) {
    Assert::equals(sha1($value), Hashing::sha1()->new()->digest($value)->hex());
  }

  #[Test, Values([['', 'e3b0c44298fc1c149afbf4c8996fb92427ae41e4649b934ca495991b7852b855'], ['Test', '532eaabd9574880dbf76b9b8cc00832c20a6ec113d682299550d7a6e0f345e25']])]
  public function sha256($value, $expected) {
    Assert::equals($expected, Hashing::sha256()->new()->digest($value)->hex());
  }

  #[Test, Values([['', 'cf83e1357eefb8bdf1542850d66d8007d620e4050b5715dc83f4a921d36ce9ce47d0d13c5d85f2b0ff8318d2877eec2f63b931bd47417a81a538327af927da3e'], ['Test', 'c6ee9e33cf5c6715a1d148fd73f7318884b41adcb916021e2bc0e800a5c5dd97f5142178f6ae88c8fdd98e1afb0ce4c8d2c54b5f37b30b7da1997bb33b0b8a31']])]
  public function sha512($value, $expected) {
    Assert::equals($expected, Hashing::sha512()->new()->digest($value)->hex());
  }

  #[Test, Values([['', '00000000'], ['Test', '07556ca6']])]
  public function murmur3_32($value, $expected) {
    Assert::equals($expected, Hashing::murmur3_32()->new()->digest($value)->hex());
  }

  #[Test, Values([['', 'f02aa77dfa1b8523d1016610da11cbb9'], ['Test', 'bc09c55042aa9b3a2ea395f564dfe810']])]
  public function murmur3_128($value, $expected) {
    Assert::equals($expected, Hashing::murmur3_128()->new(0x2a)->digest($value)->hex());
  }

  #[Test, Values([['', '00000000'], ['Test', '784dd132']])]
  public function algorithm_crc32b($value) {
    Assert::equals(hash('crc32b', $value), Hashing::algorithm('crc32b')->new()->digest($value)->hex());
  }

  #[Test, Expect(IllegalArgumentException::class)]
  public function unknown_algorithm() {
    Hashing::algorithm('not-a-hashing-algorithm')->new();
  }

  #[Test]
  public function algorithms() {
    Assert::instance('[:text.hash.Algorithm]', Hashing::algorithms());
  }

  #[Test]
  public function update_and_digest() {
    Assert::equals(
      Hashing::md5()->new()->update('Test')->digest(),
      Hashing::md5()->new()->digest('Test')
    );
  }

  #[Test]
  public function digest_shortcut() {
    Assert::equals(
      Hashing::md5()->digest('Test'),
      Hashing::md5()->new()->digest('Test')
    );
  }

  #[Test, Values(['md5', 'murmur3_32', 'murmur3_128']), Expect(IllegalStateException::class)]
  public function cannot_be_reused_after_digest_called($algorithm) {
    $fixture= Hashing::algorithm($algorithm)->new(); 

    $fixture->digest('Test');
    $fixture->digest('Test');
  }

  #[Test, Values(['md5', 'murmur3_32', 'murmur3_128'])]
  public function can_pass_secrets($algorithm) {
    $fixture= Hashing::algorithm($algorithm);

    Assert::equals($fixture->digest('test'), $fixture->digest(new Secret('test')));
  }

  #[Test, Values(['md5', 'murmur3_32', 'murmur3_128'])]
  public function can_pass_bytes($algorithm) {
    $fixture= Hashing::algorithm($algorithm);

    Assert::equals($fixture->digest('test'), $fixture->digest(new Bytes('test')));
  }
}