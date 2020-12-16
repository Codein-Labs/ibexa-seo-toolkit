<?php declare(strict_types=1);

namespace Codein\eZPlatformSeoToolkit\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class AnalyzeContentControllerTest.
 */
class AnalyzeContentControllerTest extends WebTestCase
{
    private $client;

    protected function setUp(): void
    {
        $this->client = $this->createClient();

        $this->client->request('post', '/api/ezp/v2/user/sessions', [], [], [
            'CONTENT_TYPE' => 'application/vnd.ez.api.SessionInput+json',
            'ACCEPT' => 'application/vnd.ez.api.Session+json',
        ], \json_encode(['SessionInput' => [
            'login' => 'admin',
            'password' => 'publish',
        ]]));
        parent::setUp();
    }

    public function testMissingDadRequest()
    {
        $data = [
            'keyword' => 'ezpublish',
        ];

        $this->client->request('POST', '/api/seo/analysis', [], [], ['CONTENT_TYPE' => 'application/json'], \json_encode($data));

        $response = $this->client->getResponse();
        $this->assertSame(Response::HTTP_UNPROCESSABLE_ENTITY, $response->getStatusCode());
        $this->assertSame(
            [
                'code' => 422,
                'message' => 'codein_seo_toolkit.analyzer.error.data_transfered',
            ],
            \json_decode($this->client->getResponse()->getContent(), true)
        );
    }

    public function testEmptyDataBadRequest()
    {
        $data = [];

        $this->client->request('POST', '/api/seo/analysis', [], [], ['CONTENT_TYPE' => 'application/json'], \json_encode($data));

        $response = $this->client->getResponse();
        $this->assertSame(Response::HTTP_UNPROCESSABLE_ENTITY, $response->getStatusCode());
        $this->assertSame(
            [
                'code' => 422,
                'message' => 'codein_seo_toolkit.analyzer.error.data_transfered',
            ],
            \json_decode($this->client->getResponse()->getContent(), true)
        );
    }

    public function testInvalidJsonDataBadRequest()
    {
        $this->client->request('POST', '/api/seo/analysis', [], [], ['CONTENT_TYPE' => 'application/json'], '{"keyword"}');

        $response = $this->client->getResponse();
        $this->assertSame(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
        $this->assertSame(
            [
                'code' => 400,
                'message' => 'Invalid json message received',
            ],
            \json_decode($this->client->getResponse()->getContent(), true)
        );
    }

    public function testSuccessRequest()
    {
        $data = [
            'contentId' => 58,
            'versionNo' => 2,
            'locationId' => 59,
            'contentTypeIdentifier' => 'article',
            'languageCode' => 'eng-GB',
            'siteaccess' => 'site',
            'fields' => [
                [
                    'fieldIdentifier' => 'body',
                    'fieldValue' => "<?xml version='1.0' encoding='UTF-8'?><root version='5.0-variant ezpublish-1.0'><para>This is a paragraph.</para><title>This is a heading.</title></root>",
                ],
            ],
        ];

        $this->client->request('POST', '/api/seo/analysis', [], [], ['CONTENT_TYPE' => 'application/json'], \json_encode($data));

        $response = $this->client->getResponse();
        $this->assertSame(Response::HTTP_OK, $response->getStatusCode());

        $expectedContent = [
            'codein_seo_toolkit.analyzer.category.lisibility' => [
                'InternalLinksAnalyzer' => [
                    'status' => 'low',
                    'data' => [
                        'count' => 0,
                        'recommended' => 1,
                    ],
                ],
                'OneH1TagMaximumAnalyzer' => [
                    'status' => 'high',
                    'data' => [
                        'count' => 1,
                    ],
                ],
                'OutboundLinksAnalyzer' => [
                    'status' => 'low',
                    'data' => [
                        'count' => 0,
                        'recommended' => 1,
                    ],
                ],
                'SeoTitleWidthAnalyzer' => [
                    'status' => 'high',
                    'data' => [
                        'charCount' => 29,
                    ],
                ],
                'WordCountAnalyzer' => [
                    'status' => 'low',
                    'data' => [
                        'count' => 8,
                    ],
                ],
            ],
            'codein_seo_toolkit.analyzer.category.keyword' => [
                'KeywordInTitlesAnalyzer' => [
                    'status' => 'low',
                    'data' => [
                        'ratio' => 0,
                    ],
                ],
                'KeywordInUrlSlugAnalyzer' => [],
                'KeywordLengthAnalyzer' => [
                    'status' => 'low',
                    'data' => [],
                ],
                'MetaDescriptionContainsKeywordAnalyzer' => [],
                'TitleTagContainsKeywordAnalyzer' => [],
            ],
        ];

        $this->assertSame($expectedContent, \json_decode($this->client->getResponse()->getContent(), true));
    }
}
