<?php

namespace App\Interface;

use App\Exception\EnvVariableNotDefinedException;

interface EnvVariableResolverInterface {

	public function get(string $key, $default = null): string;

	/**
	 * @throws EnvVariableNotDefinedException
	 */
	public function getOrFail(string $key): string;

	public function has(string $key): bool;

}
