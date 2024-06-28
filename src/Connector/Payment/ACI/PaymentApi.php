<?php

namespace App\Connector\Payment\ACI;

use App\Connector\Payment\ACI\Model\CardResponseModel;
use App\Connector\Payment\ACI\Model\CreatePaymentModel;
use App\Connector\Payment\ACI\Model\CreatePaymentResponseModel;
use App\Connector\Payment\ACI\Model\ResultDetailsResponseModel;
use App\Connector\Payment\ACI\Model\ResultResponseModel;
use App\Connector\Payment\ACI\Model\RiskResponseModel;

// TODO: retrieve envs using a service
class PaymentApi
{
    private \GuzzleHttp\Client $client;

    public function __construct()
    {
        $stack = new \GuzzleHttp\HandlerStack();
        $stack->setHandler(new \GuzzleHttp\Handler\CurlHandler());

        // TODO: Error handling and retrying handling
        $this->client = new \GuzzleHttp\Client([
            'base_uri' => 'https://eu-test.oppwa.com',
            'timeout' => 15,
            'http_errors' => false,
            'handler' => $stack,
            'headers' => [
                'Authorization' => 'Bearer ' . $_ENV['ACI_API_KEY'],
            ],
			'verify' => $_ENV['APP_ENV'] === 'prod',
        ]);

    }

    public function createPayment(CreatePaymentModel $createPaymentModel): CreatePaymentResponseModel {
        $response = $this->client->post(
            "/v1/payments",
            [
				'form_params' => [
					'entityId' => $createPaymentModel->entityId,
					'amount' => $createPaymentModel->amount,
					'currency' => $createPaymentModel->currency,
					'paymentBrand' => $createPaymentModel->paymentBrand,
					'paymentType' => $createPaymentModel->paymentType,
					'card.number' => $createPaymentModel->card->number,
					'card.expiryMonth' => $createPaymentModel->card->expiryMonth,
					'card.expiryYear' => $createPaymentModel->card->expiryYear,
					'card.cvv' => $createPaymentModel->card->cvc
				],
            ]
        );

        $body = json_decode($response->getBody()->getContents(), true);
        if (!is_array($body)) {
            throw new \Exception('Invalid response from ACI');
        }

        $createPaymentResponseModel = new CreatePaymentResponseModel();
        $createPaymentResponseModel
            ->setId(strval($body['id']))
            ->setPaymentType(strval($body['paymentType']))
            ->setPaymentBrand(strval($body['paymentBrand']))
            ->setAmount(floatval($body['amount']))
            ->setCurrency(strval($body['currency']))
            ->setDescriptor(strval($body['descriptor']))
            ->setBuildNumber(strval($body['buildNumber']))
            ->setTimestamp(strval($body['timestamp']))
            ->setNdc(strval($body['ndc']))
            ->setSource(strval($body['source']))
            ->setPaymentMethod(strval($body['paymentMethod']))
            ->setShortId(strval($body['shortId']))
        ;

        if (isset($body['result'])) {
            $createPaymentResponseModel
                ->setResult(
                    (new ResultResponseModel())
                        ->setCode(strval($body['result']['code']))
                        ->setDescription(strval($body['result']['description']))
                );
        }

        if (isset($body['resultDetails'])) {
            $createPaymentResponseModel
                ->setResultDetails(
                    (new ResultDetailsResponseModel())
                        ->setClearingInstituteName(strval($body['resultDetails']['clearingInstituteName']))
                );
        }

        if (isset($body['card'])) {
            $createPaymentResponseModel
                ->setCard(
                    (new CardResponseModel())
                        ->setBin(strval($body['card']['bin']))
                        ->setLast4Digits(strval($body['card']['last4Digits']))
                        ->setExpiryMonth(strval($body['card']['expiryMonth']))
                        ->setExpiryYear(strval($body['card']['expiryYear']))
                );
        }

        if (isset($body['risk'])) {
            $createPaymentResponseModel
                ->setRisk(
                    (new RiskResponseModel())
                        ->setScore(strval($body['risk']['score']))
                );
        }

        return $createPaymentResponseModel;
    }
}
