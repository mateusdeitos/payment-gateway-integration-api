<?php

namespace App\Connector\Payment\ACI\Model;

class CardDetailsModel implements \JsonSerializable {

	public function __construct(
		public readonly string $number,	
		public readonly int $expiryMonth,
		public readonly int $expiryYear,
		public readonly int $cvc,
	)
	{
		
	}


	public function jsonSerialize(): mixed {
		return get_object_vars($this);
	}
	
}
