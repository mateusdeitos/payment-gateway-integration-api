<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;
/**
 * Payload for POST /api/v1/{connectorSlug}/payment
 */
class CreatePaymentDTO {

	/**
	 * The amount in cents
	 */
	#[Assert\NotBlank()]
	#[Assert\Positive()]
	public int $amount = 0;

	/**
	 * The currency code, e.g. EUR
	 */
	#[Assert\NotBlank()]
	#[Assert\Length(exactly: 3)]
	public string $currency = "";

	/**
	 * The card number without spaces
	 */
	#[Assert\NotBlank()]
	public string $cardNumber = "";

	/**
	 * The card expiration year as 4 digits
	 */
	#[Assert\NotBlank()]
	public int $cardExpYear = 0;

	/**
	 * The card expiration month as 2 digits
	 */
	#[Assert\NotBlank()]
	#[Assert\Range(min: 1, max: 12)]
	public int $cardExpMonth = 0;

	/**
	 * The card CVV
	 */
	#[Assert\NotBlank()]
	public int $cardCvv = 0;

}
