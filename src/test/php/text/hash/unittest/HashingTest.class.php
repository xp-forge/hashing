<?php namespace text\hash\unittest;

use lang\IllegalArgumentException;
use text\hash\Hashing;
use unittest\TestCase;

class HashingTest extends TestCase {

  #[@test, @values(map= [
  #  ''    => 'd41d8cd98f00b204e9800998ecf8427e',
  # 'Test' => '0cbc6611f5540bd0809a388dc95a615b'
  #])]
  public function md5($value) {
    $this->assertEquals(md5($value), Hashing::md5()->update($value)->digest()->hex());
  }

  #[@test, @values(map= [
  #  ''     => 'da39a3ee5e6b4b0d3255bfef95601890afd80709',
  #  'Test' => '640ab2bae07bedc4c163f679a746f7ab7fb5d1fa',
  #])]
  public function sha1($value) {
    $this->assertEquals(sha1($value), Hashing::sha1()->update($value)->digest()->hex());
  }

  #[@test, @values(map= [
  #  ''     => 'e3b0c44298fc1c149afbf4c8996fb92427ae41e4649b934ca495991b7852b855',
  #  'Test' => '532eaabd9574880dbf76b9b8cc00832c20a6ec113d682299550d7a6e0f345e25',
  #])]
  public function sha256($value, $expected) {
    $this->assertEquals($expected, Hashing::sha256()->update($value)->digest()->hex());
  }

  #[@test, @values(map= [
  #  ''     => 'cf83e1357eefb8bdf1542850d66d8007d620e4050b5715dc83f4a921d36ce9ce47d0d13c5d85f2b0ff8318d2877eec2f63b931bd47417a81a538327af927da3e',
  #  'Test' => 'c6ee9e33cf5c6715a1d148fd73f7318884b41adcb916021e2bc0e800a5c5dd97f5142178f6ae88c8fdd98e1afb0ce4c8d2c54b5f37b30b7da1997bb33b0b8a31',
  #])]
  public function sha512($value, $expected) {
    $this->assertEquals($expected, Hashing::sha512()->update($value)->digest()->hex());
  }

  #[@test, @values(map= [
  #  ''     => '00000000',
  #  'Test' => '07556ca6',
  #])]
  public function murmur3_32($value, $expected) {
    $this->assertEquals($expected, Hashing::murmur3_32()->update($value)->digest()->hex());
  }

  #[@test, @values(map= [
  #  ''    => '00000000',
  # 'Test' => '784dd132',
  #])]
  public function algorithm_crc32b($value) {
    $this->assertEquals(hash('crc32b', $value), Hashing::algorithm('crc32b')->update($value)->digest()->hex());
  }

  #[@test, @expect(IllegalArgumentException::class)]
  public function unknown_algorithm() {
    Hashing::algorithm('not-a-hashing-algorithm');
  }

  #[@test]
  public function algorithms() {
    $this->assertInstanceOf('string[]', Hashing::algorithms());
  }
}