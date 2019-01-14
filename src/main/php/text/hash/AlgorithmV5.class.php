<?php namespace text\hash;

class AlgorithmV5 {
  private $f;

  /** @param function(var...): text.hash.Hash $new */
  public function __construct($new) {
    $this->f= ['new' => $new];
  }

  /**
   * Shortcut for `new()->digest()`.
   *
   * @param  string $string
   * @param  var... $args
   * @return text.hash.HashCode
   */
  public function digest($string, ... $args) {
    return $this->f['new'](...$args)->digest($string);
  }

  /**
   * Instantiates the algorithm (call interceptot for `$algo->new()`).
   *
   * @param  string $name
   * @param  var[] $args
   * @return text.hash.Hash
   */
  public function __call($name, $args) {
    return $this->f[$name](...$args);
  }
}