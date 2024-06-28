<?php

namespace App\Services\Payment;

use App\DTO\CreatedPaymentResponseDTO;
use App\DTO\CreatePaymentDTO;
use App\Enum\ConnectorIntegrationEnum;
use App\Interface\PaymentConnectorFactoryInterface;
use App\Services\ConstraintViolationParserService;
use Psr\Log\LoggerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CreatePaymentService {

	public function __construct(
		private PaymentConnectorFactoryInterface $paymentConnectorFactory,
		private ValidatorInterface $validator,
		private LoggerInterface $logger,
	) {}

	public function run(
		ConnectorIntegrationEnum $connectorSlug,
		CreatePaymentDTO $createPaymentDTO
	): CreatedPaymentResponseDTO  {
		$paymentConnector = $this->paymentConnectorFactory->getInstanceForCreatePayment($connectorSlug);
		$response = $paymentConnector->createPayment($createPaymentDTO);

		// TODO: encapsulate this in a service
		$violations = ConstraintViolationParserService::parse($this->validator->validate($response));
		if (count($violations) > 0) {
			$message = [];
			foreach ($violations as $key => $value) {
				$message[] = $key . ': ' . $value;
			}

			$message = implode(', ', $message);

			$this->logger->alert(
				'Invalid response from payment connector, violations: ' . $message,
			);
		}

		return $response;
	}
}
