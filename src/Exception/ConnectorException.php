<?php

namespace App\Exception;

class ConnectorException extends \Exception {

	public function __construct(
		string $message = "",
		private string $originalMessage = "",
		int $code = 0,
		\Throwable $previous = null
	) {
		parent::__construct($message, $code, $previous);
	}

	public function getOriginalMessage(): string {
		return $this->originalMessage;
	}

	public function getStatusCode(): int {
		return $this->getCode();
	}
	
}
