<?php

namespace Davidjr82\PhpPDFGeneratorComponents\Support;

use Davidjr82\PhpPDFGeneratorComponents\Components\LatexComponent;
use Davidjr82\PhpPDFGeneratorComponents\Components\WrapperComponent;
use Davidjr82\PhpPDFGeneratorComponents\Contracts\RenderableInterface;

class LatexUtils
{
    /**
     * Series of substitutions to sanitise text for use in LaTeX.
     *
     * http://stackoverflow.com/questions/2627135/how-do-i-sanitize-latex-input
     * Target document should \usepackage{textcomp}
     */
    public static function escape($text)
    {
        // Prepare backslash/newline handling
        $text = str_replace("\n", '\\\\', $text); // Rescue newlines
        $text = preg_replace('/[\x00-\x1F\x7F-\xFF]/', '', $text); // Strip all non-printables
        $text = str_replace('\\\\', "\n", $text); // Re-insert newlines and clear \\
        $text = str_replace('\\', '\\\\', $text); // Use double-backslash to signal a backslash in the input (escaped in the final step).

        // Symbols which are used in LaTeX syntax
        $text = str_replace('{', '\\{', $text);
        $text = str_replace('}', '\\}', $text);
        $text = str_replace('$', '\$', $text);
        $text = str_replace('&', '\\&', $text);
        $text = str_replace('#', '\\#', $text);
        $text = str_replace('^', '\\textasciicircum{}', $text);
        $text = str_replace('_', '\\_', $text);
        $text = str_replace('~', '\\textasciitilde{}', $text);
        $text = str_replace('%', '\\%', $text);

        // Brackets & pipes
        $text = str_replace('<', '\\textless{}', $text);
        $text = str_replace('>', '\\textgreater{}', $text);
        $text = str_replace('|', '\\textbar{}', $text);

        // Quotes
        $text = str_replace('"', '\\textquotedbl{}', $text);
        $text = str_replace("'", '\\textquotesingle{}', $text);
        $text = str_replace('`', '\\textasciigrave{}', $text);

        // Clean up backslashes from before
        $text = str_replace('\\\\', '\\textbackslash{}', $text); // Substitute backslashes from first step.
        $text = str_replace("\n", '\\\\', trim($text)); // Replace newlines (trim is in case of leading \\)

        return $text;
    }

    public static function extractPackages(WrapperComponent $document_container, array $before_packages = []): array
    {
        $element_packages = array_reduce(
                $document_container->getElements(),
                function(?array $packages, RenderableInterface $element) {
                    return array_unique(array_merge($packages ?? [], $element->getPackages()));
                }, []
            );

        return array_unique(array_merge($before_packages, $element_packages));
    }

    public static function createDocumentContainer()
    {
        $start_document = (new LatexComponent)->setCode('\begin{document}');
        $end_document = (new LatexComponent)->setCode('\end{document}');

        return new WrapperComponent($start_document, $end_document);
    }
}
