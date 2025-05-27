@extends('payments.layout')

@section('content')
<section class="bg-white py-8 antialiased dark:bg-gray-900 md:py-16">
    <form action="{{ route('payments.store') }}" method="POST" class="mx-auto max-w-screen-xl px-4 2xl:px-0">
        @csrf
        @if ($errors->any())
            <div class="mb-5">
                <div class="p-4 mb-4 text-sm rounded-lg bg-gray-800 text-red-400" role="alert">
                    @foreach ($errors->all() as $error)
                        <span class="font-medium">{{ $error }}</span>
                    @endforeach
                </div>
            </div>
        @endif
        <div class="mt-6 sm:mt-8 lg:flex lg:items-start lg:gap-12 xl:gap-16">
            <div class="min-w-0 flex-1 space-y-8">
                <div class="space-y-4">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Tipo de Pagamento</h3>
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                        <div class="rounded-lg border border-gray-200 bg-gray-50 p-4 ps-4 dark:border-gray-700 dark:bg-gray-800">
                            <div class="flex items-start">
                                <div class="flex h-5 items-center">
                                    <input id="credit-card" aria-describedby="credit-card-text" type="radio" name="payment_method" value="credit_card" class="h-4 w-4 border-gray-300 bg-white text-blue-600 focus:ring-2 focus:ring-blue-600 dark:border-gray-600 dark:bg-gray-700 dark:ring-offset-gray-800 dark:focus:ring-blue-600" checked />
                                </div>
                                <div class="ms-4 text-sm">
                                    <label for="credit-card" class="font-medium leading-none text-gray-900 dark:text-white"> Cartão de Crédito </label>
                                </div>
                            </div>
                        </div>
                        <div class="rounded-lg border border-gray-200 bg-gray-50 p-4 ps-4 dark:border-gray-700 dark:bg-gray-800">
                            <div class="flex items-start">
                                <div class="flex h-5 items-center">
                                    <input id="pix" aria-describedby="pix-text" type="radio" name="payment_method" value="pix" class="h-4 w-4 border-gray-300 bg-white text-blue-600 focus:ring-2 focus:ring-blue-600 dark:border-gray-600 dark:bg-gray-700 dark:ring-offset-gray-800 dark:focus:ring-blue-600" />
                                </div>
                                <div class="ms-4 text-sm">
                                    <label for="pix" class="font-medium leading-none text-gray-900 dark:text-white"> Pix </label>
                                </div>
                            </div>
                        </div>
                        <div class="rounded-lg border border-gray-200 bg-gray-50 p-4 ps-4 dark:border-gray-700 dark:bg-gray-800">
                            <div class="flex items-start">
                                <div class="flex h-5 items-center">
                                    <input id="boleto" aria-describedby="boleto-text" type="radio" name="payment_method" value="boleto" class="h-4 w-4 border-gray-300 bg-white text-blue-600 focus:ring-2 focus:ring-blue-600 dark:border-gray-600 dark:bg-gray-700 dark:ring-offset-gray-800 dark:focus:ring-blue-600" />
                                </div>
                                <div class="ms-4 text-sm">
                                    <label for="boleto" class="font-medium leading-none text-gray-900 dark:text-white"> Boleto </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="space-y-4">
                    <div class="grid grid-cols-1 gap-4">
                        <div>
                            <label for="name" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white"> Nome * </label>
                            <input type="text" id="name" name="name" placeholder="Nome completo" class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder:text-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500" required value="{{ old('name') }}"/>
                        </div>
                        <div>
                            <label for="cpf_cnpj" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white"> CPF/CNPJ * </label>
                            <input type="text" oninput="this.value = this.value.replace(/[^0-9]/g, '')" id="cpf_cnpj" name="cpf_cnpj" pattern="[0-9]{11}|[0-9]{14}" minlength="11" maxlength="14" placeholder="CPF ou CNPJ (somente números)"  class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder:text-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500" required value="{{ old('cpf_cnpj') }}"/>
                        </div>
                        <div class="input-credit-card active">
                            <label for="email" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white"> Email * </label>
                            <input type="email" id="email" name="email" placeholder="seuemail@dominio.com" class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder:text-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500" value="{{ old('email') }}"/>
                        </div>
                        <div class="input-credit-card active">
                            <label for="phone" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white"> Telefone * </label>
                            <input type="text" id="phone" name="phone" placeholder="11912345678" pattern="[0-9]{11}" minlength="11" maxlength="11" oninput="this.value = this.value.replace(/[^0-9]/g, '')" class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder:text-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500" value="{{ old('phone') }}" />
                        </div>
                        <div class="input-credit-card active">
                            <label for="postal_code" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white"> CEP * </label>
                            <input type="text" id="postal_code" name="postal_code" placeholder="00000-000" pattern="[0-9]{8}" minlength="8" maxlength="8" oninput="this.value = this.value.replace(/[^0-9]/g, '')" class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder:text-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500" value="{{ old('postal_code') }}" />
                        </div>
                        <div class="input-credit-card active">
                            <label for="address_number" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white"> Número do Endereço * </label>
                            <input type="text" id="address_number" name="address_number" placeholder="000" class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder:text-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500" value="{{ old('address_number') }}" />
                        </div>
                        <div class="input-credit-card active">
                            <label for="card_number" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white"> Número do Cartão * </label>
                            <input type="text" id="card_number" name="card_number" placeholder="123456789012345" pattern="[0-9]{16}" oninput="this.value = this.value.replace(/[^0-9]/g, '')" minlength="16" maxlength="16" class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder:text-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500" value="{{ old('card_number') }}" />
                        </div>
                        <div class="input-credit-card active gap-2 grid-cols-3 md:grid-cols-3 lg:grid-cols-3 xl:grid-cols-3 grid">
                            <div class="input-credit-card active">
                                <label for="card_expiration_month" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white"> Mês de Expiração * </label>
                                <input type="text" id="card_expiration_month" minlength="2" maxlength="2" name="card_expiration_month" placeholder="12" pattern="[0-9]{2}" oninput="this.value = this.value.replace(/[^0-9]/g, '')" class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder:text-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500" value="{{ old('card_expiration_month') }}" />
                            </div>
                            <div class="input-credit-card active">
                                <label for="card_expiration_year" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white"> Ano de Expiração * </label>
                                <input type="text" id="card_expiration_year" name="card_expiration_year" minlength="4" maxlength="4" placeholder="2025" class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder:text-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500" value="{{ old('card_expiration_year') }}" />
                            </div>
                            <div class="input-credit-card active">
                                <label for="card_ccv" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white"> CVV * </label>
                                <input type="text" id="card_ccv" name="card_ccv" placeholder="123" class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder:text-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500" value="{{ old('card_ccv') }}" />
                            </div>
                        </div>
                        <div>
                            <div class="space-y-3">
                                <button type="submit" class="flex w-full items-center justify-center rounded-lg bg-blue-700 px-5 py-2.5 text-sm font-medium text-white hover:bg-blue-800 focus:outline-none focus:ring-4  focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Confirmar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</section>

<style>
    .input-credit-card {
        display: none;
    }

    .input-credit-card.active {
        display: grid;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', () => {
    const paymentMethods = document.querySelectorAll('input[name="payment_method"]');
    const creditCardBlocks = document.querySelectorAll('.input-credit-card');

    paymentMethods.forEach((method) => {
        method.addEventListener('change', () => {
            const isCreditCard = method.value === 'credit_card';

            creditCardBlocks.forEach((block) => {
                const inputs = block.querySelectorAll('input');

                if (isCreditCard) {
                    block.classList.add('active');
                    inputs.forEach(input => input.required = true);
                } else {
                    block.classList.remove('active');
                    inputs.forEach(input => input.required = false);
                }
            });
        });
    });
});


</script>
@endsection
