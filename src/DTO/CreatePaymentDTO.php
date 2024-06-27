<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;
class CreatePaymentDTO {

	#[Assert\NotBlank()]
	public int $amount = 0;

	#[Assert\NotBlank()]
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
