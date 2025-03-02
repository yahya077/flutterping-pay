<?php

namespace yahya077\FlutterpingPay\Pages\Payment;

use Flutterping\Resources\Event\ActionEvent;
use Flutterping\Resources\Json;
use yahya077\FlutterpingPay\Pages\Payment\States\FetchPaymentDetailState;
use yahya077\FlutterpingPay\Pages\Payment\States\PaymentApprovedState;
use yahya077\FlutterpingPay\Pages\Payment\States\PaymentFailedState;
use yahya077\FlutterpingPay\Pages\Payment\States\StartPaymentState;
use Flutterping\Concept\State;
use Flutterping\Concept\StatefulPage;
use Flutterping\Resources\PageNotifier;
use Flutterping\Resources\Value\DynamicValue;
use yahya077\FlutterpingPay\Pages\Payment\Widgets\PaymentDetailWidget;

class PaymentPage extends StatefulPage
{
    public static function getInitialStateName(): string
    {
        return static::getFetchPaymentDetailState()::getName();
    }

    public static function instance()
    {
        return config('flutterping-pay.page.class');
    }

    public function getStates(): array
    {
        return [
            static::getFetchPaymentDetailState(),
            static::getStartPaymentState(),
            static::getPaymentFailedState(),
            static::getPaymentApprovedState(),
        ];
    }

    public static function getStateId(): string
    {
        return config('flutterping-pay.page.stateId');
    }

    public static function getRouteStateId(): string
    {
        return config('flutterping-pay.page.routeStateId');
    }

    public static function getRoutePath(): string
    {
        return config('flutterping-pay.page.routePath');
    }

    public static function getRouteName(): string
    {
        return config('flutterping-pay.page.routeName');
    }

    public static function getParentStateId(): string
    {
        return config('flutterping-pay.page.parentStateId');
    }

    public function getPageNotifiers(): array
    {
        return [
            (new PageNotifier(sprintf("%s_isMyCardsVisible", static::getRouteStateId()), defaultValue: new DynamicValue(true))),
        ];
    }

    public static function getFetchPaymentDetailState(): State
    {
        return FetchPaymentDetailState::make();
    }

    public static function getStartPaymentState(): State
    {
        return StartPaymentState::make();
    }

    public static function getPaymentFailedState(): State
    {
        return PaymentFailedState::make();
    }

    public static function getPaymentApprovedState(): State
    {
        return PaymentApprovedState::make();
    }

    public static function onFail(): ActionEvent
    {
        return static::getStateEvent(PaymentFailedState::getName());
    }

    public static function onSuccess(): ActionEvent
    {
        return static::getStateEvent(PaymentApprovedState::getName());
    }

    public static function getDetailWidget(): Json
    {
        return new PaymentDetailWidget();
    }
}
