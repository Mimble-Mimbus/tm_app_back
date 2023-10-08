<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class GetOrganizationTest extends WebTestCase
{

    public function setUp(): void
    {
        parent::setUp();
    }


    public function testGetOrganizationDetails(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/api/apirest/get_organization/1');
        $response = $client->getResponse();
        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/json');
    }
}
