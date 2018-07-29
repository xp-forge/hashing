<?php namespace text\hash\unittest;

use text\hash\Murmur128;
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
    yield [0x00, 0x00000000, '00000000000000000000000000000000', ''];
    yield [0x00, 0x248bfa47, 'cbd8a7b341bd9b025b1e906a48ae1d19', 'hello'];
    yield [0x00, 0x149bbb7f, '342fac623a5ebc8e4cdcbc079642414d', 'hello, world'];
    yield [0x00, 0xe31e8a70, 'b89e5988b737affc664fc2950231b2cb', '19 Jan 2038 at 3:14:07 AM'];
    yield [0x00, 0xd5c48bfc, 'cd99481f9ee902c9695da1a38987b6e7', 'The quick brown fox jumps over the lazy dog.'];

    yield [0x01, 0x514e28b7, '4610abe56eff5cb551622daa78f83583', ''];
    yield [0x01, 0xbb4abcad, 'a78ddff5adae8d10128900ef20900135', 'hello'];
    yield [0x01, 0x6f5cb2e9, '8b95f808840725c61597ed5422bd493b', 'hello, world'];
    yield [0x01, 0xf50e1f30, '2a929de9c8f97b2f56a41d99af43a2db', '19 Jan 2038 at 3:14:07 AM'];
    yield [0x01, 0x846f6a36, 'fb3325171f9744daaaf8b92a5f722952', 'The quick brown fox jumps over the lazy dog.'];

    yield [0x2a, 0x087fcd5c, 'f02aa77dfa1b8523d1016610da11cbb9', ''];
    yield [0x2a, 0xe2dbd2e1, 'c4b8b3c960af6f082334b875b0efbc7a', 'hello'];
    yield [0x2a, 0x7ec7c6c2, 'b91864d797caa956d5d139a55afe6150', 'hello, world'];
    yield [0x2a, 0x58f745f6, 'fd8f19ebdc8c6b6ad30fdc310fa08ff9', '19 Jan 2038 at 3:14:07 AM'];
    yield [0x2a, 0xc02d1434, '74f33c659cda5af74ec7a891caf316f0', 'The quick brown fox jumps over the lazy dog.'];
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
  public function hash32($seed, $digest32, $digest128, $bytes) {
    $hash= new Murmur32($seed);
    $hash->update($bytes);

    $this->assertEquals($digest32, $hash->digest()->int());
  }

  #[@test, @values('results')]
  public function hash128($seed, $digest32, $digest128, $bytes) {
    $hash= new Murmur128($seed);
    $hash->update($bytes);

    $this->assertEquals($digest128, $hash->digest()->hex());
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