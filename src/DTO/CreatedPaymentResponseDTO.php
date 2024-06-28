<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class CreatedPaymentResponseDTO {
	
	public function __construct(
		#[Assert\NotBlank()]
		public readonly ?string $transactionId,

		#[Assert\NotBlank()]
		public readonly ?\DateTime $createdAt,
		
		#[Assert\NotBlank()]
		public readonly null|int|float $amount,
		
		#[Assert\NotBlank()]
		public readonly ?string $currency,

		#[Assert\Length(exactly: 6)]
		public readonly ?string $cardBin
	) {
		
	}

}
