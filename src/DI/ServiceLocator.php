<?php

namespace App\DI;

use Exception;

class ServiceLocator
{
    private array $services;

    public function set(string $name, $service)
    {
        $this->services[$name] = $service;
    }

    /**
     * @throws Exception
     */
    public function get(string $name)
    {
        if (!isset($this->services[$name])) {
            throw new Exception("service `$name` not found");
        }

        if (is_callable($this->services[$name])) {
            $this->services[$name] = $this->services[$name]($this);
        }

        return $this->services[$name];
    }
}