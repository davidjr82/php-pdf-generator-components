<?php

namespace Davidjr82\PhpPDFGeneratorComponents\Components;

use Davidjr82\PhpPDFGeneratorComponents\Contracts\ComponentBase;
use Davidjr82\PhpPDFGeneratorComponents\Contracts\RenderableInterface;

class RawTextComponent extends ComponentBase implements RenderableInterface
{
    public function __construct()
    {
        $this->setFormatRawText();
    }
}
