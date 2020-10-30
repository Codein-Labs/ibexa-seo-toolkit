<?php declare(strict_types=1);

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class AnalyzeContentControllerTest.
 */
class AnalyzeContentControllerTest extends WebTestCase
{
    public function testBadRequest()
    {
        $data = [
            'keyword' => 'ezpublish',
        ];

        $client = $this->createClient();
        $client->request('POST', '/api/seo/analyze', [], [], [], \json_encode($data));

        $response = $client->getResponse();
        $this->assertSame(Response::HTTP_UNPROCESSABLE_ENTITY, $response->getStatusCode());
        $this->assertSame(
            [
                'code' => 422,
                'message' => 'Validation Failed',
                'errors' => [
                    'children' => [
                        'keyword' => [],
                        'isPillarPage' => [],
                        'contentTypeIdentifier' => [
                            'errors' => [
                                'This value should not be blank.',
                            ],
                        ],
                        'fields' => [
                            'errors' => [
                                'This collection should contain 1 element or more.',
                            ],
                        ],
                    ],
                ],
            ],
            \json_decode($client->getResponse()->getContent(), true)
        );
    }

    public function testSuccessRequest()
    {
        $data = [
            'keyword' => 'ezpublish',
            'contentTypeIdentifier' => 1,
            'fields' => [
                [
                    'fieldIdentifier' => 'description',
                    'fieldValue' => "<?xml version='1.0' encoding='UTF-8'?><root version='5.0-variant ezpublish-1.0'><para>This is a paragraph.</para><title>This is a heading.</title></root>",
                ],
            ],
        ];

        $client = $this->createClient();
        $client->request('POST', '/api/seo/analyze', [], [], [], \json_encode($data));

        $response = $client->getResponse();

        $this->assertSame(Response::HTTP_OK, $response->getStatusCode());
        $this->assertSame(
            [
                'description' => [
                    'WordCountAnalyzer' => [
                        'items' => [
                            'This' => 4,
                            'is' => 5,
                            'a' => 6,
                            'paragraph' => 3,
                            'heading' => 7,
                        ],
                        'totalCount' => 8,
                    ],
                ],
            ],
            \json_decode($client->getResponse()->getContent(), true)
        );
    }
}
