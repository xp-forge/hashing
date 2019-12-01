<?php namespace text\hash;

class Murmur128 implements Hash {
  const C1 = '9782798678568883157';
  const C2 = '5545529020109919103';
  const FF = "\xff\xff\xff\xff\xff\xff\xff\xff";

  private $hash, $values, $length;

  /**
   * Creates a new Murmur32 hash instance
   *
   * @param  int $seed
   */
  public function __construct($seed= 0) {
    $this->hash= [$seed, $seed];
    $this->values= '';
    $this->length= 0;
  }

  /**
   * Converts a number to bytes
   *
   * @param  int $number
   * @return string
   */
  private static function bytes($number) {
    $value= '';
    while (bccomp($number, 0) > 0) {
      $value.= substr(pack('V', bcmod($number, 0x1000000)), 0, -1);
      $number= bcdiv($number, 0x1000000);
    }
    return $value;
  }

  /**
   * Converts bytes to a number
   *
   * @param  string $bytes
   * @return int
   */
  private static function number($bytes) {
    $len= strlen($bytes);
    $len+= (3 * $len) % 4;
    $bytes= str_pad($bytes, $len, "\0", STR_PAD_RIGHT);
    $r= 0;
    for ($i= $len - 1; $i >= 0; $i-= 4) {
      $r= bcadd(
        bcmul($r, '4294967296'),
        0x1000000 * ord($bytes[$i]) + current(unpack('V', substr($bytes, $i - 3, 3)."\0"))
      );
    }      
    return $r;
  }

  /**
   * uint64 xor
   *
   * @param  int $a
   * @param  int $b
   * @return int
   */
  private static function xor64($a, $b) {
    $a= self::bytes($a);
    $b= self::bytes($b);
    $l= max(strlen($a), strlen($b));
    return self::number(str_pad($a, $l, "\0", STR_PAD_RIGHT) ^ str_pad($b, $l, "\0", STR_PAD_RIGHT));
  }

  /**
   * uint64 multiplication
   *
   * @param  int $l
   * @param  int $r
   * @return int
   */
  private static function mul($l, $r) {
    $b= self::bytes(bcmul($l, $r, 0));
    return self::number($b & self::FF.str_repeat("\0", max(0, strlen($b) - 8)));
  }

  /**
   * uint64 division
   *
   * @param  int $l
   * @param  int $r
   * @return int
   */
  private static function div($l, $r) {
    $b= self::bytes(bcdiv($l, $r, 0));
    return self::number($b & self::FF.str_repeat("\0", max(0, strlen($b) - 8)));
  }

  /**
   * uint64 addition
   *
   * @param  int $l
   * @param  int $r
   * @return int
   */
  private static function add($l, $r) {
    $b= self::bytes(bcadd($l, $r, 0));
    return self::number($b & self::FF.str_repeat("\0", max(0, strlen($b) - 8)));
  }

  /**
   * uint64 rotate left
   *
   * @param  int $x
   * @param  int $r
   * @return int
   */
  private static function rotl64($x, $r) {
    $a= self::bytes(self::mul($x, 1 << $r));
    $b= self::bytes(self::div($x, 1 << 64 - $r));
    $l= max(strlen($a), strlen($b));
    return self::number(str_pad($a, $l, "\0", STR_PAD_RIGHT) | str_pad($b, $l, "\0", STR_PAD_RIGHT));
  }

  /**
   * uint64 fmix
   *
   * @param  int $k
   * @return int
   */
  private static function fmix64($k) {
    $k= self::xor64($k, self::div($k, 1 << 33, 0));
    $k= self::mul($k, '18397679294719823053');
    $k= self::xor64($k, self::div($k, 1 << 33, 0));
    $k= self::mul($k, '14181476777654086739');
    $k= self::xor64($k, self::div($k, 1 << 33, 0));
    return $k;
  }

  /**
   * Incrementally update the hash with a string.
   *
   * @param  string $string
   * @return self
   */
  public function update($string) {

    // Merge remainder with new byte values
    list($h1, $h2)= $this->hash;
    $bytes= $this->values.$string;

    // Consume as many 16-byte-chunks as possible
    for ($i= 0, $blocks= (int)(strlen($bytes) / 16) * 16; $i < $blocks; $i+= 16) {
      $k1= self::number(substr($string, $i, 8));
      $k2= self::number(substr($string, $i + 8, 8));

      $k1= self::mul($k1, self::C1);
      $k1= self::rotl64($k1, 31);
      $k1= self::mul($k1, self::C2);
      $h1= self::xor64($h1, $k1);

      $h1= self::rotl64($h1, 27);
      $h1= self::add($h1, $h2);
      $h1= self::add(self::mul($h1, 5), 0x52dce729);

      $k2= self::mul($k2, self::C2);
      $k2= self::rotl64($k2, 33);
      $k2= self::mul($k2, self::C1);
      $h2= self::xor64($h2, $k2);

      $h2= self::rotl64($h2, 31);
      $h2= self::add($h2, $h1);
      $h2= self::add(self::mul($h2, 5), 0x38495ab5);
    }

    // Store remainder
    $this->values= substr($bytes, $i);
    $this->length+= strlen($string);
    $this->hash= [$h1, $h2];

    return $this;
  }

  /**
   * Returns the final hash digest
   *
   * @param  string $string Optional string value
   * @return text.hash.HashCode
   */
  public function digest($string= null) {
    if (null !== $string) {
      $this->update($string);
    }

    list($h1, $h2)= $this->hash;
    $k1= $k2= 0;
    switch (strlen($this->values)) {
      case 15: $k2 ^= ord($this->values[14]) << 48;
      case 14: $k2 ^= ord($this->values[13]) << 40;
      case 13: $k2 ^= ord($this->values[12]) << 32;
      case 12: $k2 ^= ord($this->values[11]) << 24;
      case 11: $k2 ^= ord($this->values[10]) << 16;
      case 10: $k2 ^= ord($this->values[9]) << 8;
      case 9: $k2 ^= ord($this->values[8]);
        $k2= self::mul($k2, self::C2);
        $k2= self::rotl64($k2, 33);
        $k2= self::mul($k2, self::C1);
        $h2= self::xor64($h2, $k2);

      case 8: $k1 ^= ord($this->values[7]) << 56;
      case 7: $k1 ^= ord($this->values[6]) << 48;
      case 6: $k1 ^= ord($this->values[5]) << 40;
      case 5: $k1 ^= ord($this->values[4]) << 32;
      case 4: $k1 ^= ord($this->values[3]) << 24;
      case 3: $k1 ^= ord($this->values[2]) << 16;
      case 2: $k1 ^= ord($this->values[1]) << 8;
      case 1: $k1 ^= ord($this->values[0]);
        $k1= self::mul($k1, self::C1);
        $k1= self::rotl64($k1, 31);
        $k1= self::mul($k1, self::C2);
        $h1= self::xor64($h1, $k1);
    }

    $h1= self::xor64($h1, $this->length);
    $h2= self::xor64($h2, $this->length);

    $h1= self::add($h1, $h2);
    $h2= self::add($h2, $h1);

    $h1= self::fmix64($h1);
    $h2= self::fmix64($h2);

    $h1= self::add($h1, $h2);
    $h2= self::add($h2, $h1);

    $a= self::bytes($h1);
    $b= self::bytes($h2);

    return new BytesHashCode(strrev(
      str_pad(substr($b, 0, 8), 8, "\0", STR_PAD_RIGHT).
      str_pad(substr($a, 0, 8), 8, "\0", STR_PAD_RIGHT)
    ));
  }
}