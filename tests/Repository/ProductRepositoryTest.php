<?php

namespace App\Tests\Repository;

use App\Repository\ProductRepository;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Liip\TestFixturesBundle\Services\DatabaseTools\AbstractDatabaseTool;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ProductRepositoryTest extends KernelTestCase
{
    /**
     * @var AbstractDatabaseTool $databaseTool
     */
    protected AbstractDatabaseTool $databaseTool;
    private ContainerInterface $myContainer;

    public function setUp(): void
    {
        parent::setUp();

        // Booting of the kernel
        self::bootKernel();
        $this->myContainer = static::getContainer();
        $this->databaseTool = $this->myContainer->get(DatabaseToolCollection::class)->get();
    }

    public function testCount()
    {
        $this->databaseTool->loadAliceFixture([
            __DIR__ . '/ProductRepositoryTestFixtures.yaml'
        ]);

        $products = $this->myContainer->get(ProductRepository::class)->count([]);

        $this->assertEquals(10, $products);
    }

    public function testFindAll()
    {
        $this->databaseTool->loadAliceFixture([
            __DIR__ . '/ProductRepositoryTestFixtures.yaml'
        ]);

        $products = $this->myContainer->get(ProductRepository::class)->findAll();

        $this->assertCount(10, $products);
        $this->assertNotCount(5, $products);
        $this->assertIsArray($products);
    }

    public function testFindBy()
    {
        $this->databaseTool->loadAliceFixture([
            __DIR__ . '/ProductRepositoryTestFixtures.yaml'
        ]);

        $products = $this->myContainer->get(ProductRepository::class)->findBy(['id' => 1]);

        $this->assertCount(1, $products);
        $this->assertNotCount(2, $products);
        $this->assertIsArray($products);
    }

    public function testFindOneBy()
    {
        $this->databaseTool->loadAliceFixture([
            __DIR__ . '/ProductRepositoryTestFixtures.yaml'
        ]);

        $product = $this->myContainer->get(ProductRepository::class)->findOneBy(['id' => 1]);

        $this->assertIsNotArray($product);
        $this->assertIsObject($product);
        $this->assertIsInt($product->getId());
    }
}
