<?php

namespace yahya077\FlutterpingPay\Pages\Payment\States;

use Flutterping\Concept\State;
use Flutterping\Resources\Action\LoadingAction;
use Flutterping\Resources\Action\NetworkRequestAction;
use Flutterping\Resources\Action\SubmitAction;
use Flutterping\Resources\Action\ValidateAndSaveFormAction;
use Flutterping\Resources\Navigation\ApiPath;

class StartPaymentState extends State
{
    public function getActions(): array
    {
        $formId = sprintf('%s.paymentForm', config('flutterping-pay.page.routeStateId'));

        return [
            (new ValidateAndSaveFormAction)
                ->setFormStateId($formId)
                ->then(
                    (new LoadingAction)
                        ->setMessage('Ödeme Yapılıyor')
                        ->then(
                            (new SubmitAction)
                                ->setFormStateId($formId)
                                ->setSubmitAction(
                                    (new NetworkRequestAction)
                                        ->setClient(config('flutterping-pay.flutterpingClient'))
                                        ->setPath((new ApiPath(sprintf('%s/completePayment', config('flutterping-pay.route.prefix')))))
                                        ->setMethod('POST')
                                )
                        )
                ),
        ];
    }
}
