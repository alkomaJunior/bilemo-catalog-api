<?php

namespace App\Tests\Repository;

use App\Repository\PictureRepository;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Liip\TestFixturesBundle\Services\DatabaseTools\AbstractDatabaseTool;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;

class PictureRepositoryTest extends KernelTestCase
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
            __DIR__ . '/PictureRepositoryTestFixtures.yaml'
        ]);

        $pictures = $this->myContainer->get(PictureRepository::class)->count([]);

        $this->assertEquals(10, $pictures);
    }

    public function testFindAll()
    {
        $this->databaseTool->loadAliceFixture([
            __DIR__ . '/PictureRepositoryTestFixtures.yaml'
        ]);

        $pictures = $this->myContainer->get(PictureRepository::class)->findAll();

        $this->assertCount(10, $pictures);
        $this->assertNotCount(5, $pictures);
        $this->assertIsArray($pictures);
    }

    public function testFindBy()
    {
        $this->databaseTool->loadAliceFixture([
            __DIR__ . '/PictureRepositoryTestFixtures.yaml'
        ]);

        $pictures = $this->myContainer->get(PictureRepository::class)->findBy(['id' => 1]);

        $this->assertCount(1, $pictures);
        $this->assertNotCount(2, $pictures);
        $this->assertIsArray($pictures);
    }

    public function testFindOneBy()
    {
        $this->databaseTool->loadAliceFixture([
            __DIR__ . '/PictureRepositoryTestFixtures.yaml'
        ]);

        $picture = $this->myContainer->get(PictureRepository::class)->findOneBy(['id' => 1]);

        $this->assertIsNotArray($picture);
        $this->assertIsObject($picture);
        $this->assertIsInt($picture->getId());
    }
}
