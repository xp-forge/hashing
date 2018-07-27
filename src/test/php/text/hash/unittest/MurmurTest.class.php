<?php namespace text\hash\unittest;

use text\hash\Murmur32;
use unittest\TestCase;

class MurmurTest extends TestCase {

  /**
   * Expected results
   *
   * @see    https://github.com/spaolacci/murmur3/blob/master/murmur_test.go
   * @return iterable
   */
  private function results() {
    yield [0x00, 0x00000000, ''];
    yield [0x00, 0x248bfa47, 'hello'];
    yield [0x00, 0x149bbb7f, 'hello, world'];
    yield [0x00, 0xe31e8a70, '19 Jan 2038 at 3:14:07 AM'];
    yield [0x00, 0xd5c48bfc, 'The quick brown fox jumps over the lazy dog.'];

    yield [0x01, 0x514e28b7, ''];
    yield [0x01, 0xbb4abcad, 'hello'];
    yield [0x01, 0x6f5cb2e9, 'hello, world'];
    yield [0x01, 0xf50e1f30, '19 Jan 2038 at 3:14:07 AM'];
    yield [0x01, 0x846f6a36, 'The quick brown fox jumps over the lazy dog.'];

    yield [0x2a, 0x087fcd5c, ''];
    yield [0x2a, 0xe2dbd2e1, 'hello'];
    yield [0x2a, 0x7ec7c6c2, 'hello, world'];
    yield [0x2a, 0x58f745f6, '19 Jan 2038 at 3:14:07 AM'];
    yield [0x2a, 0xc02d1434, 'The quick brown fox jumps over the lazy dog.'];
  }

  #[@test]
  public function can_create() {
    new Murmur32();
  }

  #[@test]
  public function can_create_with_seed() {
    new Murmur32(0x2a);
  }

  #[@test, @values('results')]
  public function hash32($seed, $digest, $bytes) {
    $hash= new Murmur32($seed);
    $hash->update($bytes);

    $this->assertEquals($digest, $hash->digest()->int());
  }

  #[@test]
  public function hash32_update() {
    $hash= new Murmur32(42);
    $hash->update('hello');
    $hash->update(', ');
    $hash->update('world');

    $this->assertEquals(0x7ec7c6c2, $hash->digest()->int());
  }

  #[@test]
  public function hash32_digest() {
    $this->assertEquals(
      (new Murmur32(42))->update('Test')->digest(),
      (new Murmur32(42))->digest('Test')
    );
  }
}