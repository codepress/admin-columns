<?php

declare(strict_types=1);

namespace AC;

final class Services implements Registerable
{

    private array $services;

    public function __construct(array $services = [])
    {
        $this->services = $services;
    }

    public function add(Registerable $service): self
    {
        $this->services[] = $service;

        return $this;
    }

    public function register(): void
    {
        foreach ($this->services as $service) {
            $service->register();
        }
    }

}