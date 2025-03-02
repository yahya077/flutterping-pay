<?php

namespace yahya077\FlutterpingPay\Http\Requests;

use Flutterping\Resources\Scope;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Response;
use yahya077\FlutterpingPay\Pages\Payment\States\PaymentFailedState;

class CompletePaymentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'with_selected_card' => 'boolean',
            'card_number' => 'required_if:with_selected_card,false',
            'card_holder_name' => 'required_if:with_selected_card,false',
            'card_expire_month' => 'required_if:with_selected_card,false',
            'card_expire_year' => 'required_if:with_selected_card,false',
            'card_cvc' => 'required_if:with_selected_card,false',
            'card_alias' => 'max:10',
            'selected_card' => 'required_if:with_selected_card,true', // |exists:stored_cards,id
            'note' => 'nullable|string|min:3|max:255',
            'extra' => 'nullable|array',
        ];
    }

    public function messages()
    {
        return [
            'card_number.required_if' => 'Kart numarası alanı zorunludur.',
            'card_holder_name.required_if' => 'Kart sahibi alanı zorunludur.',
            'card_expire_month.required_if' => 'Son kullanma tarihi ay alanı zorunludur.',
            'card_expire_year.required_if' => 'Son kullanma tarihi yıl alanı zorunludur.',
            'card_cvc.required_if' => 'CVC alanı zorunludur.',
            'card_alias.max' => 'Kart adı en fazla 10 karakter olabilir.',
            'selected_card.required_if' => 'Kart seçimi zorunludur.',
            'note.string' => 'Not alanı metin olmalıdır.',
            'note.min' => 'Not alanı en az 3 karakter olmalıdır.',
            'note.max' => 'Not alanı en fazla 255 karakter olmalıdır.',
        ];
    }

    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        $paymentPage = config('flutterping.page.class');
        $errors = $validator->errors();

        throw new HttpResponseException(Response::json($paymentPage::getStateEvent(PaymentFailedState::getName(), scope: (new Scope('', [
            'errors' => $errors,
        ]))), 422));
    }
}
