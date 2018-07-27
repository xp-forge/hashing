<?php namespace text\hash;

interface Hash {

  /**
   * Incrementally update the hash with a string.
   *
   * @param  string $string
   * @return self
   */
  public function update($string);

  /**
   * Returns the final hash digest
   *
   * @return text.hash.HashCode
   */
  public function digest();

}