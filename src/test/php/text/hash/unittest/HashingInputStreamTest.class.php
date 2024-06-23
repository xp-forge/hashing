<?php namespace text\hash\unittest;

use io\streams\MemoryInputStream;
use test\{Assert, Test, Values};
use text\hash\{Hashing, HashingInputStream};

class HashingInputStreamTest {

  /** @return iterable */
  private function arguments() {
    yield ['name', 'sha256'];
    yield ['algorithm', Hashing::sha256()];
    yield ['hash', Hashing::sha256()->new()];
  }

  #[Test]
  public function data_is_read_from_underlying_stream() {
    $fixture= new HashingInputStream('sha256', new MemoryInputStream('Test'));
    $bytes= $fixture->read();

    Assert::equals('Test', $bytes);
  }

  #[Test, Values(from: 'arguments')]
  public function hash_calculated_from($kind, $argument) {
    $fixture= new HashingInputStream($argument, new MemoryInputStream('Test'));
    $fixture->read();

    Assert::equals(Hashing::sha256()->digest('Test'), $fixture->digest());
  }

  #[Test, Values(from: 'arguments')]
  public function hash_calculated_partially($kind, $argument) {
    $fixture= new HashingInputStream($argument, new MemoryInputStream('ABC'));
    $fixture->read(1);
    $fixture->read(1);

    Assert::equals(Hashing::sha256()->digest('AB'), $fixture->digest());
  }

  #[Test, Values(from: 'arguments')]
  public function hash_calculated_without_read($kind, $argument) {
    $fixture= new HashingInputStream($argument, new MemoryInputStream(''));

    Assert::equals(Hashing::sha256()->digest(''), $fixture->digest());
  }
}