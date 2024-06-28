<?php

namespace App\Connector\Payment\ACI\Model;

class CreatePaymentResponseModel {
	
	protected ?string $id = null;
	protected ?string $paymentType = null;
	protected ?string $paymentBrand = null;
	protected ?float $amount = null;
	protected ?string $currency = null;
	protected ?string $descriptor = null;
	protected ?ResultResponseModel $result = null;
	protected ?ResultDetailsResponseModel $resultDetails = null;
	protected ?CardResponseModel $card = null;
	protected ?RiskResponseModel $risk = null;
	protected ?string $buildNumber = null;
	protected ?string $timestamp = null;
	protected ?string $ndc = null;
	protected ?string $source = null;
	protected ?string $paymentMethod = null;
	protected ?string $shortId = null;

	public function getId(): ?string {
		return $this->id;
	}

	public function getPaymentType(): ?string {
		return $this->paymentType;
	}

	public function getPaymentBrand(): ?string {
		return $this->paymentBrand;
	}

	public function getAmount(): ?float {
		return $this->amount;
	}

	public function getCurrency(): ?string {
		return $this->currency;
	}

	public function getDescriptor(): ?string {
		return $this->descriptor;
	}

	public function getResult(): ?ResultResponseModel {
		return $this->result;
	}

	public function getResultDetails(): ?ResultDetailsResponseModel {
		return $this->resultDetails;
	}

	public function getCard(): ?CardResponseModel {
		return $this->card;
	}

	public function getRisk(): ?RiskResponseModel {
		return $this->risk;
	}

	public function getBuildNumber(): ?string {
		return $this->buildNumber;
	}

	public function getTimestamp(): ?string {
		return $this->timestamp;
	}

	public function getNdc(): ?string {
		return $this->ndc;
	}

	public function getSource(): ?string {
		return $this->source;
	}

	public function getPaymentMethod(): ?string {
		return $this->paymentMethod;
	}

	public function getShortId(): ?string {
		return $this->shortId;
	}

	public function setId(string $id): self {
		$this->id = $id;
		return $this;
	}

	public function setPaymentType(string $paymentType): self {
		$this->paymentType = $paymentType;
		return $this;
	}

	public function setPaymentBrand(string $paymentBrand): self {
		$this->paymentBrand = $paymentBrand;
		return $this;
	}

	public function setAmount(float $amount): self {
		$this->amount = $amount;
		return $this;
	}

	public function setCurrency(string $currency): self {
		$this->currency = $currency;
		return $this;
	}

	public function setDescriptor(string $descriptor): self {
		$this->descriptor = $descriptor;
		return $this;
	}

	public function setResult(ResultResponseModel $result): self {
		$this->result = $result;
		return $this;
	}

	public function setResultDetails(ResultDetailsResponseModel $resultDetails): self {
		$this->resultDetails = $resultDetails;
		return $this;
	}

	public function setCard(CardResponseModel $card): self {
		$this->card = $card;
		return $this;
	}

	public function setRisk(RiskResponseModel $risk): self {
		$this->risk = $risk;
		return $this;
	}

	public function setBuildNumber(string $buildNumber): self {
		$this->buildNumber = $buildNumber;
		return $this;
	}

	public function setTimestamp(string $timestamp): self {
		$this->timestamp = $timestamp;
		return $this;
	}

	public function setNdc(string $ndc): self {
		$this->ndc = $ndc;
		return $this;
	}

	public function setSource(string $source): self {
		$this->source = $source;
		return $this;
	}

	public function setPaymentMethod(string $paymentMethod): self {
		$this->paymentMethod = $paymentMethod;
		return $this;
	}

	public function setShortId(string $shortId): self {
		$this->shortId = $shortId;
		return $this;
	}

}
