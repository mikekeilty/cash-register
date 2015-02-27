<?php

require('vendor/autoload.php');

use CashRegister\CashRegister as CashRegister;

$seed = array(
  1 => 'penny',
  5 => 'nickel',
  10 => 'dime',
  25 => 'quarter',
  100 => 'dollar bill',
  500 => 'five dollar bill',
  1000 => 'ten dollar bill'
);

$register = new CashRegister($seed);

$change = $register->transact(136, 1000);

echo '<pre>';
print_r($change);
echo '</pre>';