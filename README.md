# Cash Register


```
<?php

use CashRegister\CashRegister as CashRegister;

$seed = array(
    1 => 'penny',
    5 => 'nickel',
    10 => 'dime',
    25 => 'quarter',
    100 => 'dollar bill',
    500 => 'five dollar bill',
    1000 => 'ten dollar bill'
)

$register = new CashRegister($seed);

$change = $register->transact(136, 1000);

```

Result:

```
Array
(
    [1000] => Array
        (
            [count] => 0
            [label] => ten dollar bill
        )

    [500] => Array
        (
            [count] => 1
            [label] => five dollar bill
        )

    [100] => Array
        (
            [count] => 3
            [label] => dollar bill
        )

    [25] => Array
        (
            [count] => 2
            [label] => quarter
        )

    [10] => Array
        (
            [count] => 1
            [label] => dime
        )

    [5] => Array
        (
            [count] => 0
            [label] => nickel
        )

    [1] => Array
        (
            [count] => 4
            [label] => penny
        )

)

```
