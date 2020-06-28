<?php

namespace Davidjr82\PhpPDFGeneratorComponents\Components;

use Exception;
use Davidjr82\PhpPDFGeneratorComponents\Contracts\ComponentBase;
use Davidjr82\PhpPDFGeneratorComponents\Contracts\RenderableInterface;

class WrapperComponent extends ComponentBase implements RenderableInterface
{
    private RenderableInterface $start;
    private array $elements;
    private RenderableInterface $end;

    public function __construct(?RenderableInterface $start = null, ?RenderableInterface $end = null)
    {
        $this->start = $start;
        $this->elements = [];
        $this->end = $end;
    }


    public function setCode(string $code): self
    {
        throw new Exception("A wrapper component doesn't accept code by itself. Use ->addElement(\$element) method instead.");
        return $this;
    }

    public function setFormat(int $format): self
    {
        throw new Exception("A wrapper component doesn't accept format by itself. Use ->addElement(\$element) method instead.");
        return $this;
    }

    public function addElement(RenderableInterface $element): self
    {
        $this->elements[] = $element;
        return $this;
    }

    public function getElements(): array
    {
        foreach ($this->elements as $element) {
            $elements = array_merge($elements ?? [], $element->getElements());
        }

        return $elements;
    }


    public function render(): string
    {
        $output = [];

        if($this->start) {
            $output[] = $this->start->render();
        }

        foreach ($this->elements as $element) {
            $output[] = $element->render();
        }

        if($this->end) {
            $output[] = $this->end->render();
        }

        return implode('', $output);
    }
}
