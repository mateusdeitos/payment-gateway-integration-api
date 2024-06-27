<?php

namespace App\Connector\Payment\Shift4\Model;

class CardDetailsModel implements \JsonSerializable {

	public function __construct(
		public readonly string $number,	
		public readonly int $expMonth,
		public readonly int $expYear,
		public readonly int $cvc,
	)
	{
		
	}


	public function jsonSerialize(): mixed {
		return get_object_vars($this);
	}
	
}
