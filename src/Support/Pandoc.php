<?php

namespace Davidjr82\PhpPDFGeneratorComponents\Support;

use Exception;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

/**
 * Original from (MIT licensed) https://github.com/ryakad/pandoc-php
 * @author Ryan Kadwell <ryan@riaka.ca>
 */
class Pandoc
{
    private string $bin_path;
    private string $tmpDir;
    private string $tmpFile;

    private array $inputFormats = [
        "commonmark",
        "creole",
        "csv",
        "docbook",
        "docx",
        "dokuwiki",
        "epub",
        "fb2",
        "gfm",
        "haddock",
        "html",
        "ipynb",
        "jats",
        "jira",
        "json",
        "latex",
        "markdown",
        "markdown_mmd",
        "markdown_phpextra",
        "markdown_strict",
        "mediawiki",
        "man",
        "muse",
        "native",
        "odt",
        "opml",
        "org",
        "rst",
        "t2t",
        "textile",
        "tikiwiki",
        "twiki",
        "vimwiki",
    ];

    private array $outputFormats = [
        "asciidoc",
        "beamer",
        "commonmark",
        "context",
        "docbook",
        "docbook5",
        "docx",
        "dokuwiki",
        "epub",
        "epub2",
        "fb2",
        "gfm",
        "haddock",
        "html",
        "html4",
        "icml",
        "ipynb",
        "jats_archiving",
        "jats_articleauthoring",
        "jats_publishing",
        "jats",
        "jira",
        "json",
        "latex",
        "man",
        "markdown",
        "markdown_mmd",
        "markdown_phpextra",
        "markdown_strict",
        "mediawiki",
        "ms",
        "muse",
        "native",
        "odt",
        "opml",
        "opendocument",
        "org",
        "pdf",
        "plain",
        "pptx",
        "rst",
        "rtf",
        "texinfo",
        "textile",
        "slideous",
        "slidy",
        "dzslides",
        "revealjs",
        "s5",
        "tei",
        "xwiki",
        "zimwiki",
    ];


    public function __construct()
    {
        if (!\in_array(PHP_OS_FAMILY, ['Linux'])) {
            throw new Exception("Unsupported Operating System");
        }

        $this->setTempPaths();
        $this->setBinPath();
    }

    private function setBinPath()
    {
        $process = new Process(['which', 'pandoc']);
        $process->run();

        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        if (empty(trim($process->getOutput()))) {
            throw new Exception('pandoc');
        }

        $this->bin_path = trim($process->getOutput());
    }

    private function setTempPaths()
    {
        $tmpfresource = tmpfile();

        if (!$tmpfresource) {
            throw new Exception("Can not create temp file");
        }

        $tmpfname = stream_get_meta_data($tmpfresource)['uri'];

        if (!is_writable($tmpfname)) {
            throw new Exception(
                sprintf('Temp file %s is not writable', $tmpfname)
            );
        }

        $this->tmpDir = \dirname($tmpfname);
        $this->tmpFile = $tmpfname;
    }

    public function convert(string $content, string $from, string $to): string
    {
        if ( ! in_array($from, $this->inputFormats)) {
            throw new Exception(
                sprintf('%s is not a valid input format for pandoc', $from)
            );
        }

        if ( ! in_array($to, $this->outputFormats)) {
            throw new Exception(
                sprintf('%s is not a valid output format for pandoc', $to)
            );
        }

        file_put_contents($this->tmpFile, $content);

        $command = sprintf(
            '%s --from=%s --to=%s %s',
            $this->bin_path,
            $from,
            $to,
            $this->tmpFile
        );

        // TODO: Refactor this code!!
        exec(escapeshellcmd($command), $output);

        return implode("\n", $output);
    }

    public function __destruct()
    {
        if (file_exists($this->tmpFile)) {
            @unlink($this->tmpFile);
        }

        foreach (glob($this->tmpFile.'*') as $filename) {
            @unlink($filename);
        }
    }
}