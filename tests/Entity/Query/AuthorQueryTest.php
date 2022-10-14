<?php

namespace App\Tests\Entity\Query;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AuthorQueryTest extends WebTestCase
{
    public function testAuthorList()
    {
        $client = static::createClient();
        $client->request(
            'POST',
            '/',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode(['query' => "{Authors {id lastName books {id title}}}"])
        );
        $this->assertResponseIsSuccessful();
        $data = json_decode($client->getResponse()->getContent(), true)['data'];
        $this->assertArrayHasKey('id', $data['Authors'][0]);
        $this->assertArrayHasKey('lastName', $data['Authors'][0]);
        $this->assertArrayHasKey('id', $data['Authors'][0]['books'][0]);
        $this->assertArrayHasKey('title', $data['Authors'][0]['books'][0]);
    }
}
