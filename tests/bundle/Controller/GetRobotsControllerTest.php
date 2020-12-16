<?php declare(strict_types=1);

namespace Codein\eZPlatformSeoToolkit\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class GetRobotsControllerTest.
 */
class GetRobotsControllerTest extends WebTestCase
{
    private $client;

    protected function setUp(): void
    {
        parent::setUp();
        $this->client = $this->createClient();

    }

    public function testSuccessRequest()
    {
        $this->client->request('GET', '/robots.txt');
        $response = $this->client->getResponse();
        $data ='User-agent: *
Crawl-Delay: 30
Disallow: /directory1/
Disallow: /directory2/
Disallow: /directory3/
Allow: /directory2/subdirectory1/
Allow: /directory2/subdirectory2/
Allow: /directory2/subdirectory3/
Allow: /directory2/subdirectory4/
Sitemap: http://localhost/sitemap1.xml
Sitemap: http://localhost/sitemap2.xml

User-agent: googlebot
Crawl-Delay: 60
Disallow: /nogooglebot/
Allow: /nogooglebot/subdirectory1/
Allow: /nogooglebot/subdirectory2/
Allow: /nogooglebot/subdirectory3/
Allow: /nogooglebot/subdirectory4/
Sitemap: http://localhost/sitemap1.xml
Sitemap: http://localhost/sitemap2.xml
';
       $this->assertEquals($data, $response->getContent());
    }
}
