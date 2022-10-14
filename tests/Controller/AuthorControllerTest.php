<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AuthorControllerTest extends WebTestCase
{
    public function testList()
    {
        $client = static::createClient();
        $client->request('GET', '/api/authors');
        $this->assertResponseIsSuccessful();
        $data = json_decode($client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('firstName', $data[0]);
        $this->assertArrayHasKey('title', $data[0]['books'][0]);
        $this->assertArrayHasKey('booksCount', $data[0]);
    }

    public function testPost()
    {
        $client = static::createClient();
        $client->request(
            'POST',
            '/api/authors',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode(['firstName' => 'John', 'lastName' => 'Doe', 'books' => [1, 2]])
        );
        $this->assertResponseIsSuccessful();
    }

    public function testPostInvalid()
    {
        $client = static::createClient();
        $client->request(
            'POST',
            '/api/authors',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode(['books' => [-1]])
        );
        $data = json_decode($client->getResponse()->getContent(), true);
        $this->assertResponseStatusCodeSame(400);
        $this->assertEquals('This value should not be blank.', $data['errors']['lastName'][0]);
        $this->assertEquals('This value should not be blank.', $data['errors']['firstName'][0]);
        $this->assertEquals('The selected choice is invalid.', $data['errors']['books'][0]);
    }
}
