<?php declare(strict_types=1);

use Codein\eZPlatformSeoToolkit\Analyzer\RichText\WordCountAnalyzer;
use EzSystems\EzPlatformRichText\eZ\FieldType\RichText\Value;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Class WordCountAnalyzerTest.
 */
class WordCountAnalyzerTest extends KernelTestCase
{
    private $container;

    public function setUp(): void
    {
        self::bootKernel();

        $this->container = self::$kernel->getContainer();
    }

    public function testWordCount()
    {
        /** @var WordCountAnalyzer $ipsum */
        $ipsum = $this->container->get(WordCountAnalyzer::class);
        $xml = '<?xml version="1.0" encoding="UTF-8"?>
<section xmlns="http://docbook.org/ns/docbook" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:ezxhtml="http://ez.no/xmlns/ezpublish/docbook/xhtml" xmlns:ezcustom="http://ez.no/xmlns/ezpublish/docbook/custom" version="5.0-variant ezpublish-1.0">
  <title>This is a heading.</title>
  <para>This is a paragraph.</para>
</section>
';
        $value = new Value($xml);
        $fieldDefinition = new \eZ\Publish\Core\Repository\Values\ContentType\FieldDefinition(
            [
                'id' => 24,
                'fieldTypeIdentifier' => 'ezrichtext',
            ]
        );
        $response = $ipsum->analyze($fieldDefinition, $value);
        $this->assertArrayHasKey('items', $response);
        $this->assertArrayHasKey('totalCount', $response);
        $this->assertSame($response['totalCount'], 8);
    }
}
