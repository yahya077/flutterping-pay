<?php

namespace yahya077\FlutterpingPay\Pages\Payment\States;

use Flutterping\Concept\State;
use Flutterping\Resources\Action\AlertAction;
use Flutterping\Resources\Action\LoadingAction;
use Flutterping\Resources\Action\NavigationAction;
use Flutterping\Resources\Navigation\NavigationPath;
use Flutterping\Resources\UI\Color;
use Flutterping\Resources\Widgets\Text;

class PaymentApprovedState extends State
{
    public function getActions(): array
    {
        return [
            (new LoadingAction())->setIsLoading(true),
            (new AlertAction())->setContent((new Text("Ödeme Başarılı")))->setColor(Color::fromRGB(23, 173, 63)),
            (new NavigationAction())->setPath((new NavigationPath('navigateBack'))->setNavigatorKey(config('flutterping.navigator_key'))),
        ];
    }
}
