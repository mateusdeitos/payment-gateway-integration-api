<?php

namespace App\Services\Payment;

use App\DTO\CreatedPaymentResponseDTO;
use App\DTO\CreatePaymentDTO;
use App\Enum\ConnectorIntegrationEnum;
use App\Interface\PaymentConnectorFactoryInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Validator\ConstraintViolationInterface;
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
		$errors = $this->validator->validate($response);
		if (count($errors) > 0) {
			$message = join(', ', array_map(
				fn(ConstraintViolationInterface $error) => $error->getPropertyPath() . ': ' . $error->getMessage(),
				(array) $errors
			));

			$this->logger->alert(
				'Invalid response from payment connector, violations: ' . $message,
			);
		}

		return $response;
	}
}
