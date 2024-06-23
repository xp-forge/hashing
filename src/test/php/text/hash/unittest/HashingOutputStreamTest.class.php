<?php namespace text\hash\unittest;

use io\streams\MemoryOutputStream;
use test\{Assert, Test, Values};
use text\hash\{Hashing, HashingOutputStream};

class HashingOutputStreamTest {

  /** @return iterable */
  private function arguments() {
    yield ['name', 'sha256'];
    yield ['algorithm', Hashing::sha256()];
    yield ['hash', Hashing::sha256()->new()];
  }

  #[Test]
  public function data_is_written_to_underlying_stream() {
    $out= new MemoryOutputStream();
    $fixture= new HashingOutputStream('sha256', $out);
    $fixture->write('Test');

    Assert::equals('Test', $out->bytes());
  }

  #[Test, Values(from: 'arguments')]
  public function hash_calculated_from($kind, $argument) {
    $fixture= new HashingOutputStream($argument, new MemoryOutputStream());
    $fixture->write('Test');

    Assert::equals(Hashing::sha256()->digest('Test'), $fixture->digest());
  }

  #[Test, Values(from: 'arguments')]
  public function hash_calculated_from_all($kind, $argument) {
    $fixture= new HashingOutputStream($argument, new MemoryOutputStream());
    $fixture->write('A');
    $fixture->write('B');
    $fixture->write('C');

    Assert::equals(Hashing::sha256()->digest('ABC'), $fixture->digest());
  }

  #[Test, Values(from: 'arguments')]
  public function hash_calculated_without_write($kind, $argument) {
    $fixture= new HashingOutputStream($argument, new MemoryOutputStream());

    Assert::equals(Hashing::sha256()->digest(''), $fixture->digest());
  }
}