<?php declare(strict_types=1);

use Codein\eZPlatformSeoToolkit\Analyzer\RichText\WordCountAnalyzer;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpKernel\Kernel;

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



       $ipsum = $this->container->get('knpu_lorem_ipsum.knpu_ipsum');

       // ...
   }
}
