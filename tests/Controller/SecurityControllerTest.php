<?php

namespace App\Tests\Controller;

use App\Controller\SecurityController;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Liip\TestFixturesBundle\Services\DatabaseTools\AbstractDatabaseTool;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class SecurityControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    protected ?AbstractDatabaseTool $databaseTool;

    protected function setUp(): void
    {
        parent::setUp();
        $this->client = static::createClient();
        $myContainer = static::getContainer();
        $this->databaseTool = $myContainer->get(DatabaseToolCollection::class)->get();
    }

    public function testAuthentication()
    {
        $this->databaseTool->loadAliceFixture([
            self::$kernel->getProjectDir() . '/tests/Repository/UserRepositoryTestFixtures.yaml'
        ]);

         $crawler = $this->client->request(
             'POST',
             '/api/login_check',
             [],
             [],
             ['CONTENT_TYPE' => 'application/json'],
             '
                {
                  "username": "user1@domain.com",
                  "password": "fakePassword"
                }
            '
         );

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testSecurityController(): void
    {
        $sc = new SecurityController();

        $this->assertNull($sc->apiLogin());
    }
}
