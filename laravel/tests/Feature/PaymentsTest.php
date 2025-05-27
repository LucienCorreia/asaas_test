<?php

it('returns a successful response on use boleto', function () {
    $form = [
        'name' => 'John Doe',
        'cpf_cnpj' => '23129637010',
        'payment_method' => 'boleto',
    ];

    $this->post('/', $form)
        ->assertStatus(200)
        ->assertViewIs('payments.success')
        ->assertViewHas('payment')
        ->assertViewHas('customer');
});

it('returns a validation error response on use boleto', function () {
    $form = [
        'name' => 'John Doe',
        'cpf_cnpj' => '23129637019',
        'payment_method' => 'boleto',
    ];

    $this->post('/', $form)
        ->assertStatus(302)
        ->assertRedirectToRoute('payments.index')
        ->assertInvalid();
});

it('returns a successful response on use pix', function () {
    $form = [
        'name' => 'John Doe',
        'cpf_cnpj' => '23129637010',
        'payment_method' => 'pix',
    ];

    $this->post('/', $form)
        ->assertStatus(200)
        ->assertViewIs('payments.success')
        ->assertViewHas('payment')
        ->assertViewHas('customer');
});

it('returns a validation error response on use pix', function () {
    $form = [
        'name' => 'John Doe',
        'cpf_cnpj' => '23129637019',
        'payment_method' => 'pix',
    ];

    $this->post('/', $form)
        ->assertStatus(302)
        ->assertRedirectToRoute('payments.index')
        ->assertInvalid();
});

it('returns a successful response on use credit card', function () {
    $form = [
        'name' => 'John Doe',
        'cpf_cnpj' => '23129637010',
        'payment_method' => 'credit_card',
        'card_number' => '4242424242424242',
        'card_expiration_month' => '12',
        'card_expiration_year' => '2030',
        'card_ccv' => '123',
        'email' => 'i2tBt@example.com',
        'postal_code' => '12345678',
        'address_number' => '123',
        'phone' => '1234567890',
    ];

    $this->post('/', $form)
        ->assertStatus(200)
        ->assertViewIs('payments.success')
        ->assertViewHas('payment')
        ->assertViewHas('customer');
});

it('returns a validation error response on use credit card', function () {
    $form = [
        'name' => 'John Doe',
        'cpf_cnpj' => '23129637010',
        'payment_method' => 'credit_card',
        'card_number' => '4242424242424242',
        'card_expiration_month' => '12',
        'card_expiration_year' => '2010',
        'card_ccv' => '123',
        'email' => 'i2tBt@example.com',
        'postal_code' => '12345678',
        'address_number' => '123',
        'phone' => '1234567890',
    ];

    $this->post('/', $form)
        ->assertStatus(302)
        ->assertRedirectToRoute('payments.index')
        ->assertInvalid();
});
