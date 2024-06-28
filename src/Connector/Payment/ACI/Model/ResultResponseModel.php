<?php

namespace App\Connector\Payment\ACI\Model;

class ResultResponseModel {
	
	protected ?string $code = null;
	protected ?string $description = null;

	public function getCode(): ?string {
		return $this->code;
	}

	public function getDescription(): ?string {
		return $this->description;
	}

	public function setCode(string $code): self {
		$this->code = $code;
		return $this;
	}

	public function setDescription(string $description): self {
		$this->description = $description;
		return $this;
	}

}
