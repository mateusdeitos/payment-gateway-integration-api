<?php

namespace App\Connector\Payment\ACI\Model;

class CardResponseModel {
	protected ?string $bin = null;
	protected ?string $last4Digits = null;
	protected ?string $expiryMonth = null;
	protected ?string $expiryYear = null;

	public function getBin(): ?string {
		return $this->bin;
	}

	public function getLast4Digits(): ?string {
		return $this->last4Digits;
	}

	public function getExpiryMonth(): ?string {
		return $this->expiryMonth;
	}

	public function getExpiryYear(): ?string {
		return $this->expiryYear;
	}

	public function setBin(string $bin): self {
		$this->bin = $bin;
		return $this;
	}

	public function setLast4Digits(string $last4Digits): self {
		$this->last4Digits = $last4Digits;
		return $this;
	}

	public function setExpiryMonth(string $expiryMonth): self {
		$this->expiryMonth = $expiryMonth;
		return $this;
	}

	public function setExpiryYear(string $expiryYear): self {
		$this->expiryYear = $expiryYear;
		return $this;
	}
}
