<?php

namespace Davidjr82\PhpPDFGeneratorComponents\Contracts;

use Exception;
use cebe\markdown\latex\Markdown;
use League\HTMLToMarkdown\HtmlConverter;
use Davidjr82\PhpPDFGeneratorComponents\Support\Pandoc;
use Davidjr82\PhpPDFGeneratorComponents\Concerns\HasFormat;
use Davidjr82\PhpPDFGeneratorComponents\Support\LatexUtils;
use Davidjr82\PhpPDFGeneratorComponents\Concerns\HasPackages;

abstract class ComponentBase
{
    use HasFormat;
    use HasPackages;

    const FORMAT_LATEX = 0;
    const FORMAT_RAW_TEXT = 1;
    const FORMAT_USER_TEXT = 2;
    const FORMAT_MARKDOWN = 3;
    const FORMAT_HTML = 4;

    protected string $code;

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getElements(): array
    {
        return [ $this ];
    }

    public function render(): string
    {
        if (!isset($this->code)) {
            throw new Exception('Code must be set for the component', 1);
        }

        if (!isset($this->format)) {
            throw new Exception('Format must be set for the component', 1);
        }

        if (self::FORMAT_LATEX === $this->format) {
            return $this->code;
        }

        if (self::FORMAT_RAW_TEXT === $this->format) {
            return $this->code;
        }

        if (self::FORMAT_USER_TEXT === $this->format) {
            return LatexUtils::escape($this->code);
        }


        $pandoc = new Pandoc();
        return $pandoc->convert($this->code, $this->getPandocSourceFormat(), "latex");
    }
}
