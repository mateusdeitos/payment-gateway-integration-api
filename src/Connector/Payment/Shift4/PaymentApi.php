<?php

namespace App\Connector\Payment\Shift4;

use App\Connector\Payment\Shift4\Model\CreateCardResponseModel;
use App\Connector\Payment\Shift4\Model\CreatePaymentModel;
use App\Connector\Payment\Shift4\Model\CreatePaymentResponseModel;

// TODO: retrieve envs using a service
class PaymentApi {

	private \GuzzleHttp\Client $client;

	public function __construct() {
		$stack = new \GuzzleHttp\HandlerStack();
		$stack->setHandler(new \GuzzleHttp\Handler\CurlHandler());

		// TODO: Error handling and retrying handling
		$this->client = new \GuzzleHttp\Client([
			'base_uri' => 'https://api.shift4.com',
			'timeout' => 15,
			'http_errors' => false,
			'handler' => $stack,
			'auth' => [$_ENV['SHIFT_4_API_KEY'] ?? "", ""],
		]);

	}

	public function createPayment(CreatePaymentModel $createPaymentModel): CreatePaymentResponseModel {
		$response = $this->client->post("/charges", ['form_params' => $createPaymentModel]);

		$body = json_decode($response->getBody()->getContents(), true);
		if (!is_array($body)) {
			throw new \Exception('Invalid response from Shift4');
		}

		$createPaymentResponseModel = new CreatePaymentResponseModel();
		$createPaymentResponseModel
			->setId(strval($body['id']))
			->setCreated(strval($body['created']))
			->setObjectType(strval($body['objectType']))
			->setAmount(strval($body['amount']))
			->setCurrency(strval($body['currency']))
			->setDescription(isset($body['description']) ? strval($body['description']) : "")
			->setCard(
				(new CreateCardResponseModel())
					->setId(strval($body['card']['id']))
					->setCreated(strval($body['card']['created']))
					->setObjectType(strval($body['card']['objectType']))
					->setFirst6(strval($body['card']['first6']))
					->setLast4(strval($body['card']['last4']))
					->setFingerprint(strval($body['card']['fingerprint']))
					->setExpMonth(strval($body['card']['expMonth']))
					->setExpYear(strval($body['card']['expYear']))
					->setCardholderName(isset($body['card']['cardholderName']) ? strval($body['card']['cardholderName']) : "")
					->setCustomerId(strval($body['card']['customerId']))
					->setBrand(strval($body['card']['brand']))
					->setType(strval($body['card']['type']))
					->setIssuer(strval($body['card']['issuer']))
					->setCountry(strval($body['card']['country']))
			)
			->setCustomerId(strval($body['customerId']))
			->setCaptured(strval($body['captured']))
			->setRefunded(strval($body['refunded']))
			->setDisputed(strval($body['disputed']));		

		return $createPaymentResponseModel;
	}
}
