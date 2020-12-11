<?php declare(strict_types=1);

namespace Codein\Tests\eZPlatformSeoToolkit\Controller;

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
        $data =
            'User-agent: *
Disallow: foo
Disallow: bar
Sitemap: https://www.w3schools.com/sitemap.xml

User-agent: Googlebot
Disallow: foo
Disallow: bar
Allow: dommy
Sitemap: http://localhost/sitemap.xml

';
       $this->assertEquals($data, $response->getContent());
    }
}
