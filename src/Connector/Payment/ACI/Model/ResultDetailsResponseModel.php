<?php

namespace App\Connector\Payment\ACI\Model;

class ResultDetailsResponseModel {

	protected ?string $clearingInstituteName = null;

	public function getClearingInstituteName(): ?string {
		return $this->clearingInstituteName;
	}

	public function setClearingInstituteName(string $clearingInstituteName): self {
		$this->clearingInstituteName = $clearingInstituteName;
		return $this;
	}
	
}
