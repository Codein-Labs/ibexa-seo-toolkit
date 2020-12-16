<?php declare(strict_types=1);

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class AnalyzeContentControllerTest.
 */
class AnalyzeContentControllerTest extends WebTestCase
{
    protected function setUp() {
        $this->markTestSkipped(
            'Config needs to be set.'
        );
    }

    public function testBadRequest()
    {
        
        $data = [
            'keyword' => 'ezpublish',
        ];

        $client = $this->createClient();
        $client->request('POST', '/api/seo/analysis', [], [], [], \json_encode($data));

        $response = $client->getResponse();
        $this->assertSame(Response::HTTP_UNPROCESSABLE_ENTITY, $response->getStatusCode());
        $this->assertSame(
            [
                'code' => 422,
                'error' => 'codein_seo_toolkit.analyzer.error.analyzer_form_invalid'
            ],
            \json_decode($client->getResponse()->getContent(), true)
        );
    }
}
