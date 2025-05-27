<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class PaymentRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => ['required', 'string'],
            'payment_method' => ['required', Rule::in(['credit_card', 'pix', 'boleto'])],

            'cpf_cnpj' => [
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    $digits = preg_replace('/\D/', '', $value);
                    if (!in_array(strlen($digits), [11, 14])) {
                        $fail("O campo {$attribute} deve ser um CPF ou CNPJ válido.");
                    }
                },
            ],
        ];
    }

    public function withValidator($validator)
    {
        $validator->sometimes(
            [
                'email',
                'phone',
                'postal_code',
                'address_number',
                'card_number',
                'card_expiration_month',
                'card_expiration_year',
                'card_ccv',
            ],
            [
                'required'
            ],
            function ($input) {
                return $input->payment_method === 'credit_card';
            }
        );

        // Adiciona validações específicas quando for cartão
        $validator->sometimes('email', ['email'], function ($input) {
            return $input->payment_method === 'credit_card';
        });

        $validator->sometimes('phone', ['regex:/^\d{10,11}$/'], function ($input) {
            return $input->payment_method === 'credit_card';
        });

        $validator->sometimes('postal_code', ['regex:/^\d{8}$/'], function ($input) {
            return $input->payment_method === 'credit_card';
        });

        $validator->sometimes('address_number', ['string'], function ($input) {
            return $input->payment_method === 'credit_card';
        });

        $validator->sometimes('card_number', ['digits_between:13,19'], function ($input) {
            return $input->payment_method === 'credit_card';
        });

        $validator->sometimes('card_expiration_month', ['integer', 'between:1,12'], function ($input) {
            return $input->payment_method === 'credit_card';
        });

        $validator->sometimes('card_expiration_year', ['digits:4', 'integer'], function ($input) {
            return $input->payment_method === 'credit_card';
        });

        $validator->sometimes('card_ccv', ['digits_between:3,4'], function ($input) {
            return $input->payment_method === 'credit_card';
        });

        // Validação para expiração do cartão
        $validator->after(function ($validator) {
            if ($this->input('payment_method') === 'credit_card') {
                $month = (int) $this->input('card_expiration_month');
                $year = (int) $this->input('card_expiration_year');

                if ($month && $year) {
                    $now = Carbon::now()->startOfDay();
                    $cardExpiry = Carbon::createFromDate($year, $month, 1)->endOfMonth();

                    if ($cardExpiry->lt($now)) {
                        $validator->errors()->add('card_expiration_month', 'O cartão está expirado.');
                    }
                }
            }
        });
    }
}
