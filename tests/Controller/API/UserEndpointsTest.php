<?php

namespace App\Tests\Controller\API;

use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Liip\TestFixturesBundle\Services\DatabaseTools\AbstractDatabaseTool;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class UserEndpointsTest extends WebTestCase
{
    private KernelBrowser $client;
    protected AbstractDatabaseTool $databaseTool;

    protected function setUp(): void
    {
        parent::setUp();
        $this->client = static::createClient();
        $myContainer = static::getContainer();
        $this->databaseTool = $myContainer->get(DatabaseToolCollection::class)->get();
    }

    public function testListUsers(): void
    {
        $this->client->request('GET', '/api/users');

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertResponseHasHeader("Content-Type");
    }

    public function testShowUserNotFound(): void
    {
        $this->client->request('GET', '/api/users/19');

        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
        $this->assertResponseHasHeader("Content-Type");
    }

    public function testShowUserFound(): void
    {
        $this->databaseTool->loadAliceFixture([
            self::$kernel->getProjectDir() . '/tests/Repository/UserRepositoryTestFixtures.yaml'
        ]);

        $this->client->request('GET', '/api/users/1');

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertResponseHasHeader("Content-Type");
    }

    public function testCreateUser(): void
    {
        $this->client->request(
            'POST',
            '/api/users',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            '
                {
                  "email": "mille@some.where",
                  "password": "$argon2id$v=19$m=16,t=2,p=1$emo0b0hQWTV1NjdGNnNyQw$l9oSEgNuwdbji1VjjhWLiQ",
                  "roles": [
                     "ROLE_CUSTOMER_USER"
                   ],
                  "slug": "mille"
                }
            '
        );
        $this->assertResponseRedirects();
        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
    }

    public function testBadCreateUser(): void
    {
        $this->client->request(
            'POST',
            '/api/users',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            '
                {
                  "email": "",
                  "password": "$argon2id$v=19$m=16,t=2,p=1$emo0b0hQWTV1NjdGNnNyQw$l9oSEgNuwdbji1VjjhWLiQ",
                  "roles": [
                     "ROLE_CUSTOMER_USER"
                   ],
                  "slug": "millet"
                }
            '
        );
        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
    }

    public function testDeleteUser(): void
    {
        $this->databaseTool->loadAliceFixture([
            self::$kernel->getProjectDir() . '/tests/Repository/UserRepositoryTestFixtures.yaml'
        ]);

        $this->client->request('DELETE', '/api/users/1');

        $this->assertResponseStatusCodeSame(Response::HTTP_NO_CONTENT);
    }
}
