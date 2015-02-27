<?php

namespace CashRegister\Tests;

use PHPUnit_Framework_TestCase;
use CashRegister\CashRegister as CashRegister;
use CashRegister\TransactionException;

class CashRegisterTest extends PHPUnit_Framework_TestCase
{
    /** @test */
    public function testInstantiatingMultiples()
    {
        $seed = array(
          5 => 'Five Multiplier',
          10 => 'Ten Multiplier',
          20 => 'Twenty Multiplier',
          50 => 'Fifty Multiplier'
        );

        $register = new CashRegister($seed);

        $multiples = $register->getMultiples();

        foreach ($seed as $idx => $individualSeed) {
            static::assertArrayHasKey($idx, $multiples);
        }

        static::assertCount(count($seed), $multiples);
    }

    /** @test */
    public function testSortingMultipleContainer()
    {
        $seed = array(
          20 => 'Twenty Multiplier',
          5 => 'Five Multiplier',
          100 => 'One Hundred Multiplier',
          50 => 'Fifty Multiplier'
        );

        $register = new CashRegister($seed);

        $multiples = $register->getMultiples();
        $multiplesKeys = array_keys($multiples);

        $firstKey = array_shift($multiplesKeys);
        $lastKey = array_pop($multiplesKeys);

        static::assertEquals(100, $firstKey);
        static::assertEquals(5, $lastKey);
    }

    /** @test */
    public function testRemovingMultipleFromContainer()
    {
        $seed = array(
          20 => 'Twenty Multiplier',
          5 => 'Five Multiplier',
          100 => 'One Hundred Multiplier',
          50 => 'Fifty Multiplier'
        );

        $register = new CashRegister($seed);

        $register->removeMultiple(5);

        $multiples = $register->getMultiples();

        static::assertArrayNotHasKey(5, $multiples);

        $multiplesKeys = array_keys($multiples);

        $firstKey = array_shift($multiplesKeys);
        $lastKey = array_pop($multiplesKeys);

        static::assertEquals(100, $firstKey);
        static::assertEquals(20, $lastKey);
    }

    /** @test */
    public function testAddingMultipleToContainer()
    {
        $seed = array(
          20 => 'Twenty Multiplier',
          5 => 'Five Multiplier',
          100 => 'One Hundred Multiplier',
          50 => 'Fifty Multiplier'
        );

        $register = new CashRegister($seed);

        $register->addMultiple(array(150 => 'One hundred fifty multiplier'));

        $multiples = $register->getMultiples();

        static::assertArrayHasKey(150, $multiples);

        $multiplesKeys = array_keys($multiples);

        $firstKey = array_shift($multiplesKeys);
        $lastKey = array_pop($multiplesKeys);

        static::assertEquals(150, $firstKey);
        static::assertEquals(5, $lastKey);
    }

    /**
     * @test
     * @expectedException \CashRegister\TransactionException
     */
    public function testTransactCostGreaterThanPayment()
    {
        $seed = array(
          5 => 'Five Multiplier',
          10 => 'Ten Multiplier'
        );

        $register = new CashRegister($seed);

        $register->transact(1000, 500);
    }

    /** @test */
    public function testTransactionCostEqualsPayment()
    {
        $seed = array(
          5 => 'Five Multiplier',
          10 => 'Ten Multiplier'
        );

        $register = new CashRegister($seed);

        $change = $register->transact(1000, 1000);

        $this->_validateResponse($change, $seed);

        foreach ($change as $multiplier => $denomination) {
            static::assertEquals(0, $denomination['count']);
        }
    }

    /**
     * Method for validating change array returned from transact() method
     *
     * @param array $response
     * @param array $seed
     */
    private function _validateResponse(Array $response, Array $seed)
    {
        $found = array();
        foreach ($seed as $idx => $label) {
            foreach ($response as $multiplier => $denomination) {
                if ($idx === $multiplier) {
                    $found[$idx] = true;
                    static::assertArrayHasKey('count', $denomination);
                    static::assertArrayHasKey('label', $denomination);
                }
            }
        }

        $foundKeys = array_keys($found);
        $expectedKeys = array_keys($seed);

        $diffOne = array_diff($foundKeys, $expectedKeys);
        $diffTwo = array_diff($expectedKeys, $foundKeys);

        static::assertEquals(0, count($diffOne));
        static::assertEquals(0, count($diffTwo));
    }
}