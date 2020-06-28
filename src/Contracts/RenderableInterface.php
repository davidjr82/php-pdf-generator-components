<?php

namespace Davidjr82\PhpPDFGeneratorComponents\Contracts;

interface RenderableInterface
{
    public function render(): string;
    public function getElements(): array;
    public function getPackages(): array;
}
