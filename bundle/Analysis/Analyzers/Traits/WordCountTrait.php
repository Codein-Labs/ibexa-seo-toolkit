<?php declare(strict_types=1);

namespace Codein\IbexaSeoToolkit\Analysis\Analyzers\Traits;

use DOMDocument;

trait WordCountTrait
{
    protected function getWordCount(DOMDocument $DOMDocument): int
    {
        $htmlText = strip_tags($DOMDocument->saveHTML());
        $htmlText = html_entity_decode(preg_replace(['/\n/', '/\r/', '/\t/', '/\s+/'], [' ', ' ', ' ', ' '], $htmlText));

        return str_word_count($htmlText);
    }
}
