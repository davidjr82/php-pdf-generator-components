<?php

namespace Davidjr82\PhpPDFGeneratorComponents\Concerns;

trait HasPackages
{
    protected array $packages = [];

    public function addPackage(string $package): self
    {
        $this->packages[] = $package;
        return $this;
    }

    public function getPackages(): array
    {
        return $this->packages;
    }
}
