<?php

namespace Davidjr82\PhpPDFGeneratorComponents\Components;

use Davidjr82\PhpPDFGeneratorComponents\Contracts\ComponentBase;
use Davidjr82\PhpPDFGeneratorComponents\Contracts\RenderableInterface;

class TextCodeComponent extends WrapperComponent
{
    private RenderableInterface $element;

    public function __construct()
    {
        $start = (new LatexComponent)->setCode('\begin{verbatim}');
        $end = (new LatexComponent)->setCode('\end{verbatim}');

        parent::__construct($start, $end);

        $this->element = new RawTextComponent;
        $this->addElement($this->element);
    }

    public function setCode(string $code): self
    {
        $this->element->code = $code;
        return $this;
    }

    public function setFormat(int $format): self
    {
        $this->element->format = $format;
        return $this;
    }
}
