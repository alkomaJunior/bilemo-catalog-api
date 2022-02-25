<?php

namespace App\Tests\Controller\API;

use App\Controller\API\ProductController;
use App\Entity\Resource\Product;
use App\Repository\ProductRepository;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Liip\TestFixturesBundle\Services\DatabaseTools\AbstractDatabaseTool;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class ProductEndpointsTest extends WebTestCase
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

    public function authenticateUser(): string
    {
        $this->databaseTool->loadAliceFixture([
            self::$kernel->getProjectDir() . '/tests/Repository/UserRepositoryTestFixtures.yaml'
        ]);

        $this->client->request(
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

        $content = $this->client->getResponse()->getContent();

        return json_decode($content)->{'data'}->{'0'}->{'token'};
    }

    public function testNonAuthenticatedListProducts(): void
    {
        $this->client->request('GET', '/api/products');

        $this->assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);
    }

    public function testListProducts(): void
    {
        $this->client->request(
            'GET',
            '/api/products',
            [],
            [],
            [
                'HTTP_Content-Type' => 'application/json',
                'HTTP_Authorization' => 'Bearer ' . (string)$this->authenticateUser()
            ]
        );

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertResponseHasHeader("Content-Type");
    }

    public function testShowProductNotFound(): void
    {
        $this->client->request(
            'GET',
            '/api/products/19',
            [],
            [],
            [
                'HTTP_Content-Type' => 'application/json',
                'HTTP_Authorization' => 'Bearer ' . (string)$this->authenticateUser()
            ]
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
        $this->assertResponseHasHeader("Content-Type");
    }

    public function testShowProductFound()
    {
        $sp = (new ProductController(static::getContainer()->get(ProductRepository::class)))
            ->showProduct(new Product());

        $this->assertIsObject($sp);
    }
}
