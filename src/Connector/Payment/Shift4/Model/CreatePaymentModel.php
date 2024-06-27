<?php

namespace App\Connector\Payment\Shift4\Model;

class CreatePaymentModel implements \JsonSerializable {

	public function __construct(
		public readonly int $amount,	
		public readonly string $currency,
		public readonly string $customerId,
		public readonly CardDetailsModel $card,
	)
	{
		
	}


	public function jsonSerialize(): mixed {
		return get_object_vars($this);
	}
	
}
