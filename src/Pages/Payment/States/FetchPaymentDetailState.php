<?php

namespace yahya077\FlutterpingPay\Pages\Payment\States;

use Flutterping\Concept\State;
use Flutterping\Resources\Action\NetworkRequestAction;
use Flutterping\Resources\Navigation\ApiPath;
use Flutterping\Resources\Paintings\TextStyle;
use Flutterping\Resources\UI\Color;
use Flutterping\Resources\Widgets\AppBar;
use Flutterping\Resources\Widgets\CircularProgressIndicator;
use Flutterping\Resources\Widgets\Scaffold;
use Flutterping\Resources\Widgets\Text;
use yahya077\FlutterpingPay\Pages\Payment\PaymentPage;

class FetchPaymentDetailState extends State
{
    public function getActions(): array
    {
        return [
            PaymentPage::instance()::updateWidgetAction(
                (new Scaffold)
                    ->setAppBar((new AppBar)
                        ->setElevation(0)
                        ->setTitle((new Text(config('flutterping-pay.title')))->setStyle((new TextStyle)->setColor(new Color(255, 255, 255, 255))))
                        ->setBackgroundColor(new Color(240, 240, 240, 255)))
                    ->setBody(new CircularProgressIndicator)
            ),
            (new NetworkRequestAction)
                ->setClient(config('flutterping-pay.flutterpingClient'))
                ->setPath((new ApiPath(config('flutterping-pay.route.prefix')))),
        ];
    }
}
