<?php

namespace App\Connector\Payment\Shift4\Model;

class CreatePaymentResponseModel {
	
	protected ?string $id = null;
	protected ?int $created = null;
	protected ?string $objectType = null;
	protected ?int $amount = null;
	protected ?string $currency = null;
	protected ?string $description = null;
	protected ?CreateCardResponseModel $card = null;
	protected ?string $customerId = null;
	protected ?bool $captured = null;
	protected ?bool $refunded = null;
	protected ?bool $disputed = null;

	public function getId(): ?string {
		return $this->id;
	}

	public function getCreated(): ?int {
		return $this->created;
	}

	public function getObjectType(): ?string {
		return $this->objectType;
	}

	public function getAmount(): ?int {
		return $this->amount;
	}

	public function getCurrency(): ?string {
		return $this->currency;
	}

	public function getDescription(): ?string {
		return $this->description;
	}

	public function getCard(): ?CreateCardResponseModel {
		return $this->card;
	}

	public function getCustomerId(): ?string {
		return $this->customerId;
	}

	public function getCaptured(): ?bool {
		return $this->captured;
	}

	public function getRefunded(): ?bool {
		return $this->refunded;
	}

	public function getDisputed(): ?bool {
		return $this->disputed;
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

	public function setAmount(int $amount): self {
		$this->amount = $amount;
		return $this;
	}

	public function setCurrency(string $currency): self {
		$this->currency = $currency;
		return $this;
	}

	public function setDescription(string $description): self {
		$this->description = $description;
		return $this;
	}

	public function setCard(CreateCardResponseModel $card): self {
		$this->card = $card;
		return $this;
	}

	public function setCustomerId(string $customerId): self {
		$this->customerId = $customerId;
		return $this;
	}

	public function setCaptured(bool $captured): self {
		$this->captured = $captured;
		return $this;
	}

	public function setRefunded(bool $refunded): self {
		$this->refunded = $refunded;
		return $this;
	}

	public function setDisputed(bool $disputed): self {
		$this->disputed = $disputed;
		return $this;
	}

}
