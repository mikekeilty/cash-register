<?php

namespace CashRegister;

class CashRegister
{
    private $_multiples = array();

    private $_change = array();

    public function __construct(Array $multiples)
    {
        krsort($multiples);
        $this->_multiples = $multiples;
    }

    public function addMultiple(Array $multiple)
    {
        $key = array_keys($multiple);
        $key = $key[0];

        if(!array_key_exists($key, $this->_multiples)) {
            $this->_multiples[$key] = $multiple[$key];
            krsort($this->_multiples);
            return true;
        }

        return false;
    }

    public function removeMultiple($multiplier)
    {
        if(array_key_exists($multiplier, $this->_multiples)) {
            unset($this->_multiples[$multiplier]);
            krsort($this->_multiples);
            return true;
        }

        return false;
    }

    public function getMultiples()
    {
        return $this->_multiples;
    }

    public function transact($cost, $payment)
    {
        if($cost > $payment) {
            throw new TransactionException('Payment must be greater than or equal to cost');
        }

        $change = array();

        if($cost === $payment) {
            foreach($this->_multiples as $multiple => $label) {
                $change[$multiple]['count'] = 0;
                $change[$multiple]['label'] = $label;
            }

            return $change;
        }

        $diff = $payment - $cost;
        $temp = 0;

        foreach($this->_multiples as $multiple => $label) {

            $change[$multiple]['count'] = 0;
            $change[$multiple]['label'] = $label;
            while(true) {
                if($temp > $diff) {
                    $change[$multiple]['count']--;
                    $temp -= $multiple;
                    break;
                }

                $temp += $multiple;
                $change[$multiple]['count']++;
            }
        }

        $this->_change = $change;
        return $change;
    }
}