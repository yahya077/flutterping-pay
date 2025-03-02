# Payment Page for Flutterping Laravel Adapter

[![Latest Version on Packagist](https://img.shields.io/packagist/v/yahya077/flutterping-pay.svg?style=flat-square)](https://packagist.org/packages/yahya077/flutterping-pay)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/yahya077/flutterping-pay/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/yahya077/flutterping-pay/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/yahya077/flutterping-pay/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/yahya077/flutterping-pay/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/yahya077/flutterping-pay.svg?style=flat-square)](https://packagist.org/packages/yahya077/flutterping-pay)

This package is a Laravel adapter for [Flutterping](https://docs.flutterping.com) payment page.

## Installation

You can install the package via composer:

```bash
composer require yahya077/flutterping-pay
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="flutterping-pay-config"
```

Optionally, you can publish the views using

```bash
php artisan vendor:publish --tag="flutterping-pay-views"
```

## Usage
First, you need to configure config file `config/flutterping-pay.php` with your Flutterping credentials.
```php
return [
    "title" => "Ödeme Sayfası",
    "flutterpingClient" => "app_client",
    "route" => [
        "domain" => env('FP_APP_DOMAIN', 'localhost'),
        "prefix" => "resource/payment",
        "middleware" => ['auth:api-customer'],
        "as" => "flutterping-pay."
    ],
    "page" => [
        "class" => \App\Flutterping\Pages\Payment\PaymentPage::class, // Your custom payment page class
        "parentStateId" => 'mainStackStateId',
        "stateId" => 'paymentPageState',
        "routePath" => 'paymentPage',
        "routeStateId" => 'paymentPageStateId',
        "routeName" => 'paymentPage'
    ]
];
```

You need to extend from `yahya077\FlutterpingPay\Pages\Payment\PaymentPage` class and implement the `getDetailWidget` method to return the detail widget of the payment page.
```php
class PaymentPage extends \yahya077\FlutterpingPay\Pages\Payment\PaymentPage
{
    public static function getDetailWidget(): Json
    {
        return new PaymentDetailWidget();
    }
}
```

And also you should override default PaymentDetailWidget class to customize the payment page.
```php
class PaymentDetailWidget extends \yahya077\FlutterpingPay\Pages\Payment\Widgets\PaymentDetailWidget
{
    public function getStoredCards(): Collection
    {
        // You can customize the stored cards here, you don't have to implement this method if you don't want to show stored cards
        return $this->user->storedCards; 
    }

    public function getTotalPrice(): string
    {
        return sprintf("%s TL", "0,00"); // You can customize the total price here
    }
}
```

To get payment you need to have AbstractPaymentService model and implement the `getPaymentData` method.
```php
class PaymentService extends \yahya077\FlutterpingPay\AbstractPaymentService
{
    public function __construct(private readonly StoredCardService $storedCardService) // you can inject your services here
    {
    }

    public function completePayment(CompletePaymentParameters $completePaymentParameters): void
    {
        // payment completed
    }
}
```

Now, bind the PaymentService to the AbstractPaymentService in the service provider.
```php
$this->app->bind(\yahya077\FlutterpingPay\AbstractPaymentService::class, PaymentService::class);
```

It's all set, now you can use the payment page by adding to your route

```php
GoRoute::fromPage(PaymentPage::make())
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Yahya Hindioglu](https://github.com/yahya077)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
