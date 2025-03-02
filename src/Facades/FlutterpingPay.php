<?php

namespace yahya077\FlutterpingPay\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \yahya077\FlutterpingPay\FlutterpingPay
 */
class FlutterpingPay extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \yahya077\FlutterpingPay\FlutterpingPay::class;
    }
}
