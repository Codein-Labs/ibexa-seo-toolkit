<?php declare(strict_types=1);

namespace Codein\eZPlatformSeoToolkit\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class AnalyzeContentControllerTest.
 */
class AnalyzeContentControllerTest extends WebTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->client = $this->createClient([], [
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'publish',
        ]);
    }

    public function testMissingDadRequest()
    {
        $data = [
            'keyword' => 'ezpublish',
        ];

        $client = $this->createClient();
        $client->request('POST', '/api/seo/analysis', [], [], ['CONTENT_TYPE' => 'application/json',], \json_encode($data));

        $response = $client->getResponse();
        $this->assertSame(Response::HTTP_UNPROCESSABLE_ENTITY, $response->getStatusCode());
        $this->assertSame(
            [
                'code' => 422,
                'message' => 'codein_seo_toolkit.analyzer.error.data_transfered'
            ],
            \json_decode($client->getResponse()->getContent(), true)
        );
    }

    public function testEmptyDataBadRequest()
    {
        $data = [];

        $client = $this->createClient();
        $client->request('POST', '/api/seo/analysis', [], [], ['CONTENT_TYPE' => 'application/json',], \json_encode($data));

        $response = $client->getResponse();
        $this->assertSame(Response::HTTP_UNPROCESSABLE_ENTITY, $response->getStatusCode());
        $this->assertSame(
            [
                'code' => 422,
                'message' => 'codein_seo_toolkit.analyzer.error.data_transfered'
            ],
            \json_decode($client->getResponse()->getContent(), true)
        );
    }

    public function testInvalidJsonDataBadRequest()
    {
        $client = $this->createClient();
        $client->request('POST', '/api/seo/analysis', [], [], ['CONTENT_TYPE' => 'application/json',], '{"keyword"}');

        $response = $client->getResponse();
        $this->assertSame(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
        $this->assertSame(
            [
                'code' => 400,
                'message' => 'Invalid json message received'
            ],
            \json_decode($client->getResponse()->getContent(), true)
        );
    }

    public function testSuccessRequest()
    {
        $data = [
            "contentId" => 58,
            "versionNo" => 2,
            "locationId" => 59,
            "contentTypeIdentifier" => "article",
            "languageCode" => "eng-GB",
            "siteaccess" => "site",
            "fields" => [
                [
                    "fieldIdentifier" => "body",
                    "fieldValue" => "<?xml version='1.0' encoding='UTF-8'?><root version='5.0-variant ezpublish-1.0'><para>This is a paragraph.</para><title>This is a heading.</title></root>"
                ]
            ]
        ];

        $client = $this->createClient();
        $client->request('POST', '/api/seo/analysis', [], [], ['CONTENT_TYPE' => 'application/json',], \json_encode($data));

        $response = $client->getResponse();
        $this->assertSame(Response::HTTP_OK, $response->getStatusCode());
        $this->assertSame(
            [
                'code' => 422,
                'message' => 'codein_seo_toolkit.analyzer.error.data_transfered'
            ],
            \json_decode($client->getResponse()->getContent(), true)
        );
    }
}
