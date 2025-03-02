<?php

namespace yahya077\FlutterpingPay\Pages\Payment\States;

use Flutterping\Concept\State;
use Flutterping\Resources\Action\AlertAction;
use Flutterping\Resources\Action\LoadingAction;
use Flutterping\Resources\UI\Color;
use Flutterping\Resources\Widgets\Text;

class PaymentFailedState extends State
{
    public function getActions(): array
    {
        return [
            (new LoadingAction)
                ->setIsLoading(true),
            (new AlertAction)->setContent((new Text('Ödeme Başarısız!')))->setColor(Color::fromRGB(255, 0, 0)),
        ];
    }
}
