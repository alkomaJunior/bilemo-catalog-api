<?php

namespace App\Tests\Repository;

use App\Repository\CustomerRepository;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Liip\TestFixturesBundle\Services\DatabaseTools\AbstractDatabaseTool;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;

class CustomerRepositoryTest extends KernelTestCase
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
            __DIR__ . '/CustomerRepositoryTestFixtures.yaml'
        ]);

        $customers = $this->myContainer->get(CustomerRepository::class)->count([]);

        $this->assertEquals(10, $customers);
    }

    public function testFindAll()
    {
        $this->databaseTool->loadAliceFixture([
            __DIR__ . '/CustomerRepositoryTestFixtures.yaml'
        ]);

        $customers = $this->myContainer->get(CustomerRepository::class)->findAll();

        $this->assertCount(10, $customers);
        $this->assertNotCount(5, $customers);
        $this->assertIsArray($customers);
    }

    public function testFindBy()
    {
        $this->databaseTool->loadAliceFixture([
            __DIR__ . '/CustomerRepositoryTestFixtures.yaml'
        ]);

        $customers = $this->myContainer->get(CustomerRepository::class)->findBy(['id' => 1]);

        $this->assertCount(1, $customers);
        $this->assertNotCount(2, $customers);
        $this->assertIsArray($customers);
    }

    public function testFindOneBy()
    {
        $this->databaseTool->loadAliceFixture([
            __DIR__ . '/CustomerRepositoryTestFixtures.yaml'
        ]);

        $customer = $this->myContainer->get(CustomerRepository::class)->findOneBy(['id' => 1]);

        $this->assertIsNotArray($customer);
        $this->assertIsObject($customer);
    }
}
