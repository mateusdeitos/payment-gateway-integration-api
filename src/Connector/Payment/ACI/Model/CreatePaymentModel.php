<?php

namespace App\Connector\Payment\ACI\Model;

class CreatePaymentModel implements \JsonSerializable {

	public function __construct(
		public readonly string $entityId,
		public readonly int $amount,	
		public readonly string $currency,
		public readonly string $paymentBrand,
		public readonly string $paymentType,
		public readonly CardDetailsModel $card,
	)
	{
		
	}


	public function jsonSerialize(): mixed {
		return get_object_vars($this);
	}
	
}
