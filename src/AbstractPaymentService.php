<?php

namespace yahya077\FlutterpingPay;

use yahya077\FlutterpingPay\Contracts\PaymentServiceInterface;
use yahya077\FlutterpingPay\Schema\CompletePaymentParameters;
abstract class AbstractPaymentService implements PaymentServiceInterface
{
    public function buildParameters(array $parameters): CompletePaymentParameters
    {
        return CompletePaymentParameters::fromArray($parameters);
    }

    abstract public function completePayment(CompletePaymentParameters $completePaymentParameters): void;
}
