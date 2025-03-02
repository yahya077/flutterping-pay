<?php

namespace yahya077\FlutterpingPay\Contracts;

use yahya077\FlutterpingPay\Schema\CompletePaymentParameters;

interface PaymentServiceInterface
{
    public function completePayment(CompletePaymentParameters $completePaymentParameters): void;
}
