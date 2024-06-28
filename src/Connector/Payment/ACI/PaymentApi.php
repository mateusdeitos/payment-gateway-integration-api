<?php

namespace App\Connector\Payment\ACI;

use App\Connector\Payment\ACI\Model\CardResponseModel;
use App\Connector\Payment\ACI\Model\CreatePaymentModel;
use App\Connector\Payment\ACI\Model\CreatePaymentResponseModel;
use App\Connector\Payment\ACI\Model\ResultDetailsResponseModel;
use App\Connector\Payment\ACI\Model\ResultResponseModel;
use App\Connector\Payment\ACI\Model\RiskResponseModel;
use App\Exception\ConnectorException;
use App\Interface\EnvVariableResolverInterface;
use Psr\Http\Message\RequestInterface;

class PaymentApi
{
    private \GuzzleHttp\Client $client;

    public function __construct(
		private EnvVariableResolverInterface $envResolver,
		?callable $handler = null,
	) {
        $stack = new \GuzzleHttp\HandlerStack();
        $stack->setHandler($handler ?? new \GuzzleHttp\Handler\CurlHandler());

		$stack->push(\GuzzleHttp\Middleware::retry(
			decider: function (int $retries, RequestInterface $request, ?\Psr\Http\Message\ResponseInterface $response, ?\Throwable $exception) {
				if ($retries > 2) {
					throw new ConnectorException('Failed to connect to ACI', code: 0, previous: $exception);
				}

				if ($exception instanceof \GuzzleHttp\Exception\ConnectException) {
					return true;
				}

				return match ($response?->getStatusCode()) {
					429 => true,
					500 => true,
					502 => true,
					503 => true,
					504 => true,
					default => false
				};
			},
			delay: function (int $retries, ?\Psr\Http\Message\ResponseInterface $response, RequestInterface $request) {
				if ($response?->getHeaderLine('Retry-After')) {
					return $response?->getHeaderLine('Retry-After') * 1000;
				}

				return $retries * 1000;
			}),
		);

        $this->client = new \GuzzleHttp\Client([
            'base_uri' => 'https://eu-test.oppwa.com',
            'timeout' => 15,
            'http_errors' => false,
            'handler' => $stack,
            'headers' => [
                'Authorization' => 'Bearer ' . $this->envResolver->getOrFail('ACI_API_KEY'),
            ],
			'verify' => $this->envResolver->getOrFail('APP_ENV') === 'prod',
        ]);

    }

    public function createPayment(CreatePaymentModel $createPaymentModel): CreatePaymentResponseModel {
        $response = $this->client->post(
            "/v1/payments",
            [
				'form_params' => [
					'entityId' => $createPaymentModel->entityId,
					'amount' => round($createPaymentModel->amount / 100, 2),
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

		if ($response->getStatusCode() !== 200) {
			throw new ConnectorException('Invalid response from ACI', originalMessage: $response->getBody()->getContents());
		}

        $body = json_decode($response->getBody()->getContents(), true);
        if (!is_array($body)) {
            throw new ConnectorException('Invalid response from ACI');
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
