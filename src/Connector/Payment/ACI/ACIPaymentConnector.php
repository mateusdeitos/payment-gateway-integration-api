<?php

namespace App\Connector\Payment\ACI;

use App\Connector\Payment\ACI\Model\CardDetailsModel;
use App\Connector\Payment\ACI\Model\CreatePaymentModel;
use App\DTO\CreatedPaymentResponseDTO;
use App\DTO\CreatePaymentDTO;
use App\Interface\CreatePaymentConnectorInterface;
use App\Interface\EnvVariableResolverInterface;

class ACIPaymentConnector implements CreatePaymentConnectorInterface {

	public function __construct(
		private PaymentApi $paymentApi,
		private EnvVariableResolverInterface $envResolver
	) {}

	public function createPayment(CreatePaymentDTO $createPaymentDTO): CreatedPaymentResponseDTO {		
		$createPaymentModel = new CreatePaymentModel(
			entityId: $this->envResolver->getOrFail('ACI_ENTITY_ID'),
			amount: $createPaymentDTO->amount,
			currency: $createPaymentDTO->currency,
			paymentBrand: $this->envResolver->getOrFail('ACI_PAYMENT_BRAND'),
			paymentType: 'DB',
			card: new CardDetailsModel(
				number: $createPaymentDTO->cardNumber,
				expiryMonth: $createPaymentDTO->cardExpMonth,
				expiryYear: $createPaymentDTO->cardExpYear,
				cvc: $createPaymentDTO->cardCvv
			)
		);

		$res = $this->paymentApi->createPayment($createPaymentModel);

		$createdAt = \DateTime::createFromFormat('Y-m-d H:i:s.uP', $res->getTimestamp());
		$createdPaymentResponseDTO = new CreatedPaymentResponseDTO(
			transactionId: $res->getId(),
			createdAt: $createdAt ?: null,
			amount: $res->getAmount(),
			currency: $res->getCurrency(),
			cardBin: $res->getCard()->getBin()
		);

		return $createdPaymentResponseDTO;
	}
}
