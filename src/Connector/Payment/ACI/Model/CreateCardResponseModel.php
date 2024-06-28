<?php

namespace App\Connector\Payment\ACI\Model;

class CreateCardResponseModel {

	protected ?string $id = null;
	protected ?int $created = null;
	protected ?string $objectType = null;
	protected ?string $first6 = null;
	protected ?string $last4 = null;
	protected ?string $fingerprint = null;
	protected ?string $expMonth = null;
	protected ?string $expYear = null;
	protected ?string $cardholderName = null;
	protected ?string $customerId = null;
	protected ?string $brand = null;
	protected ?string $type = null;
	protected ?string $issuer = null;
	protected ?string $country = null;

	public function getId(): ?string {
		return $this->id;
	}

	public function getCreated(): ?int {
		return $this->created;
	}

	public function getObjectType(): ?string {
		return $this->objectType;
	}

	public function getFirst6(): ?string {
		return $this->first6;
	}

	public function getLast4(): ?string {
		return $this->last4;
	}

	public function getFingerprint(): ?string {
		return $this->fingerprint;
	}

	public function getExpMonth(): ?string {
		return $this->expMonth;
	}

	public function getExpYear(): ?string {
		return $this->expYear;
	}

	public function getCardholderName(): ?string {
		return $this->cardholderName;
	}

	public function getCustomerId(): ?string {
		return $this->customerId;
	}

	public function getBrand(): ?string {
		return $this->brand;
	}

	public function getType(): ?string {
		return $this->type;
	}

	public function getIssuer(): ?string {
		return $this->issuer;
	}

	public function getCountry(): ?string {
		return $this->country;
	}

	public function setId(string $id): self {
		$this->id = $id;
		return $this;
	}

	public function setCreated(int $created): self {
		$this->created = $created;
		return $this;
	}

	public function setObjectType(string $objectType): self {
		$this->objectType = $objectType;
		return $this;
	}

	public function setFirst6(string $first6): self {
		$this->first6 = $first6;
		return $this;
	}

	public function setLast4(string $last4): self {
		$this->last4 = $last4;
		return $this;
	}

	public function setFingerprint(string $fingerprint): self {
		$this->fingerprint = $fingerprint;
		return $this;
	}

	public function setExpMonth(string $expMonth): self {
		$this->expMonth = $expMonth;
		return $this;
	}

	public function setExpYear(string $expYear): self {
		$this->expYear = $expYear;
		return $this;
	}

	public function setCardholderName(string $cardholderName): self {
		$this->cardholderName = $cardholderName;
		return $this;
	}

	public function setCustomerId(string $customerId): self {
		$this->customerId = $customerId;
		return $this;
	}

	public function setBrand(string $brand): self {
		$this->brand = $brand;
		return $this;
	}

	public function setType(string $type): self {
		$this->type = $type;
		return $this;
	}

	public function setIssuer(string $issuer): self {
		$this->issuer = $issuer;
		return $this;
	}

	public function setCountry(string $country): self {
		$this->country = $country;
		return $this;
	}

}
