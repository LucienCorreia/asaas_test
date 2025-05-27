<?php

namespace App\Http\Controllers;

use App\Http\Requests\PaymentRequest as RequestsPaymentRequest;
use App\Integrations\Asaas\AsaasImplementation;
use App\Integrations\Asaas\Requests\ConsumerRequest;
use App\Integrations\Asaas\Requests\PaymentCreditCardRequest;
use App\Integrations\Asaas\Requests\PaymentRequest;
use App\Models\Customer;
use App\Models\Payment;
use Illuminate\Http\Request;
use App\Integrations\Asaas\Abstract\Asaas;
use App\Integrations\Asaas\Enums\BillingTypeEnum;
use App\Integrations\Asaas\Requests\CreditCardHolderInfoRequest;
use App\Integrations\Asaas\Requests\CreditCardRequest;
use Carbon\Carbon;
use GuzzleHttp\Exception\RequestException;

class PaymentController extends Controller
{

    public function __construct(
        private Asaas $asaas,
    )
    {}

    public function index()
    {
        return view('payments.index');
    }

    public function store(RequestsPaymentRequest $request)
    {
        try {
            $customer = $this->getOrCreateCustomer($request);
        } catch (RequestException $e) {
            $errorsResponse = json_decode($e->getResponse()->getBody()->getContents());
            $errors = [];

            foreach ($errorsResponse->errors as $error) {
                $errors[] = $error->description;
            }

            return back()->withErrors($errors);
        }

        if ($request->payment_method === 'credit_card') {
            return $this->createPaymentCreditCard($request, $customer);
        }

        if ($request->payment_method === 'pix') {
            return $this->createPaymentPix($customer);
        }

        if ($request->payment_method === 'boleto') {
            return $this->createPaymentBoleto($customer);
        }
    }

    private function createPaymentCreditCard(Request $request, Customer $customer)
    {
        $creditCardRequest = new PaymentCreditCardRequest(
            $customer->code,
            '100',
            Carbon::now()->addDays(30)->format('Y-m-d'),
            new CreditCardRequest(
                $request->name,
                $request->card_number,
                $request->card_expiration_month,
                $request->card_expiration_year,
                $request->card_ccv
            ),
            new CreditCardHolderInfoRequest(
                $request->name,
                $request->email,
                $request->cpf_cnpj,
                $request->postal_code,
                $request->address_number,
                $request->phone
            )
        );

        try {
            $response = $this->asaas->createPaymentCreditCard($creditCardRequest);

            $payment = Payment::create([
                'customer_id' => $customer->id,
                'code' => $response->id,
                'status' => $response->status,
                'bank_slip_url' => $response->bankSlipUrl,
                'value' => $response->value,
                'due_date' => $response->dueDate,
                'billing_type' => $response->billingType
            ]);

            return view('payments.success', ['payment' => $payment, 'customer' => $customer]);
        } catch (RequestException $e) {
            $errorsResponse = json_decode($e->getResponse()->getBody()->getContents());
            $errors = [];

            foreach ($errorsResponse->errors as $error) {
                $errors[] = $error->description;
            }

            return redirect()->route('payments.index')->withErrors($errors);
        }
    }

    private function createPaymentPix(Customer $customer)
    {
        $paymentRequest = new PaymentRequest(
            $customer->code,
            BillingTypeEnum::PIX,
            '100',
            Carbon::now()->addDays(30)->format('Y-m-d'),
        );
        try {

            $response = $this->asaas->createPayment($paymentRequest);

            $payment = Payment::create([
                'customer_id' => $customer->id,
                'code' => $response->id,
                'status' => $response->status,
                'bank_slip_url' => $response->bankSlipUrl,
                'value' => $response->value,
                'due_date' => $response->dueDate,
                'billing_type' => $response->billingType
            ]);

            $pixResponse = $this->asaas->getPix($payment->code);

            $payment->pix_payload = $pixResponse->payload;
            $payment->pix_encoded_image = $pixResponse->encodedImage;

            $payment->save();

            return view('payments.success', ['payment' => $payment, 'customer' => $customer]);
        } catch (RequestException $e) {
            $errorsResponse = json_decode($e->getResponse()->getBody()->getContents());
            $errors = [];

            foreach ($errorsResponse->errors as $error) {
                $errors[] = $error->description;
            }

            return redirect()->route('payments.index')->withErrors($errors);
        }
    }

    private function createPaymentBoleto(Customer $customer)
    {
        $paymentRequest = new PaymentRequest(
            $customer->code,
            BillingTypeEnum::BOLETO,
            '100',
            Carbon::now()->addDays(30)->format('Y-m-d'),
        );

        try {
            $response = $this->asaas->createPayment($paymentRequest);

            $payment = Payment::create([
                'customer_id' => $customer->id,
                'code' => $response->id,
                'status' => $response->status,
                'bank_slip_url' => $response->bankSlipUrl,
                'value' => $response->value,
                'due_date' => $response->dueDate,
                'billing_type' => $response->billingType
            ]);

            return view('payments.success', ['payment' => $payment, 'customer' => $customer]);
        } catch (RequestException $e) {
            $errorsResponse = json_decode($e->getResponse()->getBody()->getContents());
            $errors = [];

            foreach ($errorsResponse->errors as $error) {
                $errors[] = $error->description;
            }

            return redirect()->route('payments.index')->withErrors($errors);
        }
    }

    private function getOrCreateCustomer(Request $request): Customer
    {
        $customer = Customer::where('document_number', $request->cpf_cnpj)->first();

        if (blank($customer)) {
            $customerRequest = new ConsumerRequest(
                $request->name,
                $request->cpf_cnpj,
            );

            $customerResponse = $this->asaas->createCustomer($customerRequest);

            $customer = Customer::create([
                'document_number' => $customerResponse->cpfCnpj,
                'code' => $customerResponse->id,
                'name' => $customerResponse->name
            ]);
        }

        return $customer;
    }
}
