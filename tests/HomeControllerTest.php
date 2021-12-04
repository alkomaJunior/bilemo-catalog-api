<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Panther\Client;
use Symfony\Component\Panther\PantherTestCase;

class HomeControllerTest extends PantherTestCase
{
    private ?Client $clientPanther = null;
    private ?KernelBrowser $clientSymfony = null;
    protected function setUp(): void
    {
        parent::setUp();
        $this->clientPanther = static::createPantherClient();
        $this->clientSymfony = static::createClient();
    }

    public function testHomePage()
    {
        $crawler = $this->clientPanther->request('GET', '/');
        $this->clientSymfony->request('GET', '/');

        $this->assertStringContainsString('BileMo Catalog-API', $crawler->filter('h2')->text());
        $this->assertResponseRedirects('/api/doc', Response::HTTP_FOUND);
    }
}
