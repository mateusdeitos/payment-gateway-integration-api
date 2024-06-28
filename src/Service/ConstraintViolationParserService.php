<?php

namespace App\Service;

use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\ConstraintViolationList;

class ConstraintViolationParserService
{

	/**
	 * @return array<string, string>
	 */
	public static function parse(ConstraintViolationList $errors): array {
		$parsedErrors = [];
		/**
		 * @var ConstraintViolationInterface $violation
		 */
		foreach ($errors as $violation) {
			$parsedErrors[$violation->getPropertyPath()] = $violation->getMessage();
		}

		return $parsedErrors;
	}
}
