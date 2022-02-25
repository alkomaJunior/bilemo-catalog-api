<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class HomeControllerTest extends WebTestCase
{
    private ?KernelBrowser $clientSymfony = null;
    protected function setUp(): void
    {
        parent::setUp();
        $this->clientSymfony = static::createClient();
    }

    public function testHomePage()
    {
        $this->clientSymfony->request('GET', '/');

        $this->assertResponseRedirects('/api/doc', Response::HTTP_FOUND);
    }
}
