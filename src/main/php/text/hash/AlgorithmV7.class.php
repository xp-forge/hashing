<?php namespace text\hash;

class AlgorithmV7 {
  private $new;

  /** @param function(var...): text.hash.Hash $new */
  public function __construct($new) {
    $this->new= $new;
  }

  /**
   * Shortcut for `new()->digest()`.
   *
   * @param  string $string
   * @param  var... $args
   * @return text.hash.HashCode
   */
  public function digest($string, ... $args) {
    return ($this->new)(...$args)->digest($string);
  }

  /**
   * Instantiates the algorithm
   *
   * @param  var... $args
   * @return text.hash.Hash
   */
  public function new(... $args) {
    return ($this->new)(...$args);
  }
}