<?php

namespace App\Services\Payment;

use App\DTO\CreatedPaymentResponseDTO;
use App\DTO\CreatePaymentDTO;
use App\Enum\ConnectorIntegrationEnum;
use App\Interface\PaymentConnectorFactoryInterface;
use App\Services\ConstraintViolationParserService;
use Psr\Log\LoggerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Service responsible for creating a payment in the payment connector identified by the connectorSlug parameter
 */
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

		$this->logResponseViolations($response);

		return $response;
	}

	/**
	 * This method logs any unexpected constraint violations from the connector's response in order to monitor potential errors
	 */
	private function logResponseViolations(CreatedPaymentResponseDTO $response): void {
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
	}
}
