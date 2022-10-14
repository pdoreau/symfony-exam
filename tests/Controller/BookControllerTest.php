<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BookControllerTest extends WebTestCase
{
    public function testList()
    {
        $client = static::createClient();
        $client->request('GET', '/api/books');
        $this->assertResponseIsSuccessful();
        $data = json_decode($client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('title', $data[0]);
    }

    public function testPostSuffix()
    {
        $client = static::createClient();
        $client->request(
            'POST',
            '/api/books/add-suffix',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode(['suffix' => 'foo'])
        );
        $this->assertResponseIsSuccessful();
        $data = json_decode($client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('title', $data[0]);
    }
}
