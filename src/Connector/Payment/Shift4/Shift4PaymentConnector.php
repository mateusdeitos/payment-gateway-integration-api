<?php

namespace App\Connector\Payment\Shift4;

use App\Connector\Payment\Shift4\Model\CardDetailsModel;
use App\Connector\Payment\Shift4\Model\CreatePaymentModel;
use App\DTO\CreatedPaymentResponseDTO;
use App\DTO\CreatePaymentDTO;
use App\Interface\CreatePaymentConnectorInterface;
use App\Interface\EnvVariableResolverInterface;

class Shift4PaymentConnector implements CreatePaymentConnectorInterface {

	public function __construct(
		private PaymentApi $paymentApi,
		private EnvVariableResolverInterface $envResolver
	) {}

	public function createPayment(CreatePaymentDTO $createPaymentDTO): CreatedPaymentResponseDTO {
		
		$createPaymentModel = new CreatePaymentModel(
			amount: $createPaymentDTO->amount,
			currency: $createPaymentDTO->currency,
			customerId: $this->envResolver->getOrFail('SHIFT_4_CUSTOMER_ID') ?? "",
			card: new CardDetailsModel(
				number: $createPaymentDTO->cardNumber,
				expMonth: $createPaymentDTO->cardExpMonth,
				expYear: $createPaymentDTO->cardExpYear,
				cvc: $createPaymentDTO->cardCvv
			)
		);

		$res = $this->paymentApi->createPayment($createPaymentModel);

		$fnConvertTimestampToDatetime = function (?int $timestamp): ?\DateTime {
			if ($timestamp === null) {
				return null;
			}

			$date = date('Y-m-d H:i:s', $timestamp);
			$dateTime = \DateTime::createFromFormat('Y-m-d H:i:s', $date);
			if ($dateTime === false) {
				return null;
			}

			return $dateTime;
		};

		$createdPaymentResponseDTO = new CreatedPaymentResponseDTO(
			transactionId: $res->getId(),
			createdAt: $fnConvertTimestampToDatetime($res->getCreated()),
			amount: $res->getAmount(),
			currency: $res->getCurrency(),
			cardBin: $res->getCard()->getFirst6()
		);

		return $createdPaymentResponseDTO;
	}
}
