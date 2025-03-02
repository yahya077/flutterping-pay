<?php

namespace yahya077\FlutterpingPay\Http\Controllers;

use Flutterping\Facades\Flutterping;
use Illuminate\Support\Facades\Log;
use yahya077\FlutterpingPay\AbstractPaymentService;
use yahya077\FlutterpingPay\Http\Requests\CompletePaymentRequest;
use yahya077\FlutterpingPay\Pages\Payment\PaymentPage;

class PaymentController
{
    public function index()
    {
        return response()->flutterping(PaymentPage::instance()::updateWidgetEvent(PaymentPage::instance()::getDetailWidget()));
    }

    public function completePayment(CompletePaymentRequest $request, AbstractPaymentService $service)
    {
        $request->merge([
            'ip' => $request->getClientIp() ?? '0.0.0.0',
            'environment' => Flutterping::getPlatform() ?? 'mobile',
        ]);

        try {
            $service->completePayment($service->buildParameters($request->all()));
        } catch (\Exception $e) {
            Log::error('PaymentController::completePayment', [
                'message' => $e->getMessage(),
            ]);

            return response()->flutterping(PaymentPage::instance()::onFail());
        }

        return response()->flutterping(PaymentPage::instance()::onSuccess());
    }
}
