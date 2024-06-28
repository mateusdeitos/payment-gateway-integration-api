<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;
/**
 * Payload for POST /api/v1/{connectorSlug}/payment
 */
class CreatePaymentDTO {

	#[Assert\NotBlank()]
	#[Assert\Positive()]
	public int $amount = 0;

	#[Assert\NotBlank()]
	#[Assert\Length(exactly: 3)]
	public string $currency = "";

	#[Assert\NotBlank()]
	public string $cardNumber = "";

	#[Assert\NotBlank()]
	public int $cardExpYear = 0;

	#[Assert\NotBlank()]
	#[Assert\Range(min: 1, max: 12)]
	public int $cardExpMonth = 0;

	#[Assert\NotBlank()]
	public int $cardCvv = 0;

}
