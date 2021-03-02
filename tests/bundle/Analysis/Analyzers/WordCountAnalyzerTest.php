<?php declare(strict_types=1);

namespace Codein\IbexaSeoToolkit\Tests\Analysis\Analyzers;

use Codein\IbexaSeoToolkit\Analysis\Analyzers\WordCountAnalyzer;
use Codein\IbexaSeoToolkit\Model\AnalysisDTO;
use Codein\IbexaSeoToolkit\Model\Field;
use Doctrine\Common\Collections\ArrayCollection;
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
        $analysisDTO = new AnalysisDTO();
        $arrayCollection = new ArrayCollection();
        $arrayCollection->add((new Field())->setFieldIdentifier('test')->setFieldValue($xml));

        $analysisDTO->setFields($arrayCollection);
        $response = $ipsum->analyze($analysisDTO);

        $this->assertArrayHasKey(WordCountAnalyzer::CATEGORY, $response);
        $this->assertArrayHasKey('data', $response[WordCountAnalyzer::CATEGORY]);
        $this->assertArrayHasKey('count', $response[WordCountAnalyzer::CATEGORY]['data']);
        $this->assertSame($response[WordCountAnalyzer::CATEGORY]['data']['count'], 8);
    }
}
