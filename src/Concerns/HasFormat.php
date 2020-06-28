<?php

namespace Davidjr82\PhpPDFGeneratorComponents\Concerns;

trait HasFormat
{
    protected int $format;

    protected function setFormat(int $format): self
    {
        $this->format = $format;

        return $this;
    }

    public function setFormatLatex(): self
    {
        return $this->setFormat(self::FORMAT_LATEX);
    }

    public function setFormatRawText(): self
    {
        return $this->setFormat(self::FORMAT_RAW_TEXT);
    }

    public function setFormatUserText(): self
    {
        return $this->setFormat(self::FORMAT_USER_TEXT);
    }

    public function setFormatMarkdown(): self
    {
        return $this->setFormat(self::FORMAT_MARKDOWN);
    }

    public function setFormatHtml(): self
    {
        return $this->setFormat(self::FORMAT_HTML);
    }

    private function getPandocSourceFormat()
    {
        if (self::FORMAT_MARKDOWN === $this->format) {
            return 'gfm';
        }

        if (self::FORMAT_HTML === $this->format) {
            return 'html';
        }

        throw new Exception('Format not compatible ' . $this->format, 1);
    }
}
