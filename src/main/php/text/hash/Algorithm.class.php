<?php namespace text\hash;

class Algorithm {
  private $new;

  /** @param function(var...): text.hash.Hash $new */
  public function __construct($new) {
    $this->new= $new;
  }

  /**
   * Shortcut for `new()->digest()`.
   *
   * @param  string|util.Bytes|util.Secret $arg
   * @param  var... $args
   * @return text.hash.HashCode
   */
  public function digest($arg, ... $args) {
    return ($this->new)(...$args)->digest($arg);
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