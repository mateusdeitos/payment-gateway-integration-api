<?php

use App\Connector\Payment\ACI\PaymentApi;
use App\Connector\Payment\ACI\ACIPaymentConnector;
use App\DTO\CreatePaymentDTO;
use App\Exception\ConnectorException;
use App\Service\EnvVariableResolverService;
use GuzzleHttp\Handler\MockHandler;

describe('ACIConnectorTest', function () {
	$envResolver = new EnvVariableResolverService([
		'ACI_API_KEY' => 'key',
		'ACI_ENTITY_ID' => 'entity_id',
		'ACI_PAYMENT_BRAND' => 'VISA',
		'APP_ENV' => 'test'
	]);

    it('should return a created payment response when successful', function () use ($envResolver) {
        $paymentApi = new PaymentApi(
			$envResolver,
            new MockHandler([
                new \GuzzleHttp\Psr7\Response(
                    status: 200,
                    headers: ['Content-Type' => 'application/json'],
                    body: '{
						"id":"8ac7a4a0905bb96701905c6011e97dc7",
						"paymentType":"DB",
						"paymentBrand":"VISA",
						"amount":"51.56",
						"currency":"EUR",
						"descriptor":"6565.0156.6291 OPP_Channel ",
						"result":{
							"code":"000.100.110",
							"description":"Request successfully processed in \'Merchant in Integrator Test Mode\'"
						},
						"resultDetails":{
							"clearingInstituteName":"Elavon-euroconex_UK_Test"
						},
						"card":{
							"bin":"420000",
							"last4Digits":"0000",
							"holder":"Jane Jones",
							"expiryMonth":"05",
							"expiryYear":"2034"
						},
						"risk":{
							"score":"100"
						},
						"buildNumber":"7fbf7351147c7d36fbd97e0689ad1844295d3bf4@2024-06-27 14:08:50 +0000",
						"timestamp":"2024-06-28 01:05:18.463+0000",
						"ndc":"8a8294174b7ecb28014b9699220015ca_dbd4cfe4d14f4eaca349b10c87a330bb",
						"source":"OPP",
						"paymentMethod":"CC",
						"shortId":"6565.0156.6291"
					}'
                ),
            ]),
        );

        $aciConnector = new ACIPaymentConnector($paymentApi, $envResolver);

        $createPaymentDTO = new CreatePaymentDTO();
        $createPaymentDTO->amount = 5156;
        $createPaymentDTO->currency = 'EUR';
        $createPaymentDTO->cardNumber = '4200004242420000';
        $createPaymentDTO->cardExpMonth = '05';
        $createPaymentDTO->cardExpYear = '2034';
        $createPaymentDTO->cardCvv = '123';

        $response = $aciConnector->createPayment($createPaymentDTO);
        expect($response->transactionId)->toBe('8ac7a4a0905bb96701905c6011e97dc7');
        expect($response->createdAt)->toBeInstanceOf(\DateTime::class);
        expect($response->createdAt->format('Y-m-d H:i:s'))->toBe("2024-06-28 01:05:18");
        expect($response->amount)->toBe(51.56);
        expect($response->currency)->toBe('EUR');
        expect($response->cardBin)->toBe('420000');
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
						"id":"8ac7a4a0905bb96701905c6011e97dc7",
						"paymentType":"DB",
						"paymentBrand":"VISA",
						"amount":"51.56",
						"currency":"EUR",
						"descriptor":"6565.0156.6291 OPP_Channel ",
						"result":{
							"code":"000.100.110",
							"description":"Request successfully processed in \'Merchant in Integrator Test Mode\'"
						},
						"resultDetails":{
							"clearingInstituteName":"Elavon-euroconex_UK_Test"
						},
						"card":{
							"bin":"420000",
							"last4Digits":"0000",
							"holder":"Jane Jones",
							"expiryMonth":"05",
							"expiryYear":"2034"
						},
						"risk":{
							"score":"100"
						},
						"buildNumber":"7fbf7351147c7d36fbd97e0689ad1844295d3bf4@2024-06-27 14:08:50 +0000",
						"timestamp":"2024-06-28 01:05:18.463+0000",
						"ndc":"8a8294174b7ecb28014b9699220015ca_dbd4cfe4d14f4eaca349b10c87a330bb",
						"source":"OPP",
						"paymentMethod":"CC",
						"shortId":"6565.0156.6291"
					}'
                ),
            ]),
        );

        $aciConnector = new ACIPaymentConnector($paymentApi, $envResolver);

        $createPaymentDTO = new CreatePaymentDTO();
        $createPaymentDTO->amount = 5156;
        $createPaymentDTO->currency = 'EUR';
        $createPaymentDTO->cardNumber = '4200004242420000';
        $createPaymentDTO->cardExpMonth = '05';
        $createPaymentDTO->cardExpYear = '2034';
        $createPaymentDTO->cardCvv = '123';

        expect(fn () => $aciConnector->createPayment($createPaymentDTO))->not->toThrow(\Exception::class);
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

        $aciConnector = new ACIPaymentConnector($paymentApi, $envResolver);

        $createPaymentDTO = new CreatePaymentDTO();
        $createPaymentDTO->amount = 5156;
        $createPaymentDTO->currency = 'EUR';
        $createPaymentDTO->cardNumber = '4200004242420000';
        $createPaymentDTO->cardExpMonth = '05';
        $createPaymentDTO->cardExpYear = '2034';
        $createPaymentDTO->cardCvv = '123';
		try {
			$aciConnector->createPayment($createPaymentDTO);
			expect(1)->toBe(1);
		} catch (\Throwable $th) {
			expect($th)->toBeInstanceOf(ConnectorException::class);
		}

    });
});
