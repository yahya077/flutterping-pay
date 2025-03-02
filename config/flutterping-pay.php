<?php

use yahya077\FlutterpingPay\Pages\Payment\PaymentPage;

return [
    "title" => "Ödeme Sayfası",
    "flutterpingClient" => "app_client",
    "route" => [
        "domain" => null,
        "prefix" => "resource/payment",
        "middleware" => [],
        "as" => "flutterping-pay."
    ],
    "page" => [
        "class" => PaymentPage::class,
        "parentStateId" => null,
        "stateId" => 'paymentPageState',
        "routePath" => 'paymentPage',
        "routeStateId" => 'paymentPageStateId',
        "routeName" => 'paymentPage'
    ]
];
