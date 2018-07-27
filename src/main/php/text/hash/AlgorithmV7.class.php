<?php namespace text\hash;

class AlgorithmV7 {
  private $new;

  /** @param function(var...): text.hash.Hash $new */
  public function __construct($new) {
    $this->new= $new;
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