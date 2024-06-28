<?php

namespace App\Exception;

use Exception;

class EnvVariableNotDefinedException extends Exception {

	public function __construct(string $key) {
		parent::__construct("Env variable $key not defined");
	}
	
}
