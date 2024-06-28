<?php

namespace App\Service;

use App\Exception\EnvVariableNotDefinedException;
use App\Interface\EnvVariableResolverInterface;

class EnvVariableResolverService implements EnvVariableResolverInterface
{
    public function __construct(
        private array $envs = []
    ) {
		$this->envs = array_merge($_ENV, $_SERVER, $envs);
    }

    public function getOrFail(string $key): string
    {
        if (!$this->has($key)) {
            throw new EnvVariableNotDefinedException($key);
        }

        return $this->get($key);
    }

    public function get(string $key, $default = null): string
    {
        if (!array_key_exists($key, $this->envs)) {
            return $default;
        }

        return (string) $this->envs[$key];
    }

    public function has(string $key): bool
    {
        return array_key_exists($key, $this->envs);
    }

}
