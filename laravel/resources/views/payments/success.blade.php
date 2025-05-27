@extends('payments.layout')

@section('content')
    @if($payment->billing_type == \App\Integrations\Asaas\Enums\BillingTypeEnum::BOLETO)
    <div class="flex items-center justify-center w-full transition-opacity opacity-100 duration-750 lg:grow starting:opacity-0 justify-center items-center h-screen">
        <section class="bg-white py-8 antialiased dark:bg-gray-900 md:py-16">
            <div class="mb-5">
                <div class="p-4 mb-4 text-sm rounded-lg bg-gray-800 text-green-400 flex flex-col align-center items-center justify-center" role="alert">
                    <span class="font-medium">Boleto gerado com sucesso!</span>
                    <a class="underline font-bold" href="{{ $payment->bank_slip_url }}" target="_blank">Clique aqui para baixar o boleto</a>
                </div>
            </div>
        </section>
    </div>
    @endif
    @if($payment->billing_type == \App\Integrations\Asaas\Enums\BillingTypeEnum::CREDIT_CARD && $payment->status == \App\Integrations\Asaas\Enums\StatusEnum::CONFIRMED)
    <div class="flex items-center justify-center w-full transition-opacity opacity-100 duration-750 lg:grow starting:opacity-0 justify-center items-center h-screen">
        <section class="bg-white py-8 antialiased dark:bg-gray-900 md:py-16">
            <div class="mb-5">
                <div class="p-4 mb-4 text-sm rounded-lg bg-gray-800 text-green-400 flex flex-col align-center items-center justify-center" role="alert">
                    <span class="font-medium">Pagamento realizado com sucesso!</span>
                </div>
            </div>
        </section>
    </div>
    @endif
    @if($payment->billing_type == \App\Integrations\Asaas\Enums\BillingTypeEnum::PIX)
    <div class="flex items-center justify-center w-full transition-opacity opacity-100 duration-750 lg:grow starting:opacity-0 justify-center items-center h-screen">
        <section class="bg-white py-8 antialiased dark:bg-gray-900 md:py-16">
            <div class="mb-5 flex flex-col items-center">
                <img src="data:image/png;base64,{{ $payment->pix_encoded_image }}" alt="">
                <p class="mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ $payment->pix_payload }}</p>
            </div>
        </section>
    </div>
    @endif
@endsection
