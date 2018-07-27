<?php namespace text\hash;

interface HashCode {

  /** @return int */
  public function int();

  /** @return string */
  public function hex();

  /** @return util.Bytes */
  public function bytes();
}