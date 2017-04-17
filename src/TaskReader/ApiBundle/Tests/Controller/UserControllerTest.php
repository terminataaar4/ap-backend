<?php

namespace TaskReader\ApiBundle\Tests\Controller;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    protected function setUp()
    {
        $this->client = static::createClient();

        (new Filesystem())->copy(
            __DIR__ . '/../Fixtures/users.yml',
            $this->client->getContainer()->get('kernel')->getCacheDir() . '/users.yml',
            true
        );
    }

    public function testAll()
    {
        $client   = static::createClient();
        $crawler  = $client->request('GET', '/users.json');
        $response = $client->getResponse();

        $this->assertEquals(
            200,
            $response->getStatusCode(),
            $response->getContent()
        );

        $this->assertTrue(
            $response->headers->contains('Content-Type', 'application/json'),
            $response->headers
        );
    }
}
