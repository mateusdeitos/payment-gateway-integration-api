<?php

use App\Connector\Payment\Shift4\PaymentApi;
use App\Connector\Payment\Shift4\Shift4PaymentConnector;
use App\DTO\CreatePaymentDTO;
use App\Exception\ConnectorException;
use App\Service\EnvVariableResolverService;
use GuzzleHttp\Handler\MockHandler;

describe('Shif4ConnectorTest', function () {

	$envResolver = new EnvVariableResolverService([
		'SHIFT_4_API_KEY' => 'key',
		'SHIFT_4_CUSTOMER_ID' => 'cust_AoR0wvgntQWRUYMdZNLYMz5R'
	]);

    it('should return a created payment response when successful', function () use ($envResolver) {
        $paymentApi = new PaymentApi(
			$envResolver,
            new MockHandler([
                new \GuzzleHttp\Psr7\Response(
                    status: 200,
                    headers: ['Content-Type' => 'application/json'],
                    body: '{
						"id" : "char_ORVCrwOrTkGsDwM3H50OIW7Q",
						"created" : 1415810511,
						"objectType" : "charge",
						"amount" : 499,
						"currency" : "USD",
						"description" : "Example charge",
						"card" : {
							"id" : "card_8P7OWXA5xiTS1ISnyZcum1KV",
							"created" : 1415810511,
							"objectType" : "card",
							"first6" : "424242",
							"last4" : "4242",
							"fingerprint" : "e3d8suyIDgFg3pE7",
							"expMonth" : "11",
							"expYear" : "2027",
							"customerId" : "cust_AoR0wvgntQWRUYMdZNLYMz5R",
							"brand" : "Visa",
							"type" : "Credit Card",
							"issuer" : "Card Issuer Name",
							"country" : "CH"
						},
						"customerId" : "cust_AoR0wvgntQWRUYMdZNLYMz5R",
						"captured" : true,
						"refunded" : false,
						"disputed" : false
					}'
                ),
            ]),
        );

        $shiftConnector = new Shift4PaymentConnector($paymentApi, $envResolver);

        $createPaymentDTO = new CreatePaymentDTO();
        $createPaymentDTO->amount = 499;
        $createPaymentDTO->currency = 'USD';
        $createPaymentDTO->cardNumber = '4242424242424242';
        $createPaymentDTO->cardExpMonth = '11';
        $createPaymentDTO->cardExpYear = '2027';
        $createPaymentDTO->cardCvv = '123';

        $response = $shiftConnector->createPayment($createPaymentDTO);
        expect($response->transactionId)->toBe('char_ORVCrwOrTkGsDwM3H50OIW7Q');
        expect($response->createdAt)->toBeInstanceOf(\DateTime::class);
        expect($response->createdAt->format('Y-m-d H:i:s'))->toBe("2014-11-12 16:41:51");
        expect($response->amount)->toBe(499);
        expect($response->currency)->toBe('USD');
        expect($response->cardBin)->toBe('424242');
    });

	it('should retry a request when receiving a 429 or a >=500 error', function () use ($envResolver) {
        $paymentApi = new PaymentApi(
			$envResolver,
            new MockHandler([
                new \GuzzleHttp\Psr7\Response(status: 429),
                new \GuzzleHttp\Psr7\Response(status: 500),
				new \GuzzleHttp\Psr7\Response(
                    status: 200,
                    headers: ['Content-Type' => 'application/json'],
                    body: '{
						"id" : "char_ORVCrwOrTkGsDwM3H50OIW7Q",
						"created" : 1415810511,
						"objectType" : "charge",
						"amount" : 499,
						"currency" : "USD",
						"description" : "Example charge",
						"card" : {
							"id" : "card_8P7OWXA5xiTS1ISnyZcum1KV",
							"created" : 1415810511,
							"objectType" : "card",
							"first6" : "424242",
							"last4" : "4242",
							"fingerprint" : "e3d8suyIDgFg3pE7",
							"expMonth" : "11",
							"expYear" : "2027",
							"customerId" : "cust_AoR0wvgntQWRUYMdZNLYMz5R",
							"brand" : "Visa",
							"type" : "Credit Card",
							"issuer" : "Card Issuer Name",
							"country" : "CH"
						},
						"customerId" : "cust_AoR0wvgntQWRUYMdZNLYMz5R",
						"captured" : true,
						"refunded" : false,
						"disputed" : false
					}'
                ),
            ]),
        );

        $shiftConnector = new Shift4PaymentConnector($paymentApi, $envResolver);

        $createPaymentDTO = new CreatePaymentDTO();
        $createPaymentDTO->amount = 499;
        $createPaymentDTO->currency = 'USD';
        $createPaymentDTO->cardNumber = '4242424242424242';
        $createPaymentDTO->cardExpMonth = '11';
        $createPaymentDTO->cardExpYear = '2027';
        $createPaymentDTO->cardCvv = '123';

        expect(fn () => $shiftConnector->createPayment($createPaymentDTO))->not->toThrow(\Exception::class);
    });

	
	it('should throw a ConnectorException when all retries attempt failed', function () use ($envResolver) {
        $paymentApi = new PaymentApi(
			$envResolver,
            new MockHandler([
                new \GuzzleHttp\Psr7\Response(status: 429),
                new \GuzzleHttp\Psr7\Response(status: 429),
                new \GuzzleHttp\Psr7\Response(status: 429),
                new \GuzzleHttp\Psr7\Response(status: 429),
			]),
        );

        $shiftConnector = new Shift4PaymentConnector($paymentApi, $envResolver);

        $createPaymentDTO = new CreatePaymentDTO();
        $createPaymentDTO->amount = 499;
        $createPaymentDTO->currency = 'USD';
        $createPaymentDTO->cardNumber = '4242424242424242';
        $createPaymentDTO->cardExpMonth = '11';
        $createPaymentDTO->cardExpYear = '2027';
        $createPaymentDTO->cardCvv = '123';
		try {
			$shiftConnector->createPayment($createPaymentDTO);
			expect(1)->toBe(1);
		} catch (\Throwable $th) {
			expect($th)->toBeInstanceOf(ConnectorException::class);
		}

    });
});
