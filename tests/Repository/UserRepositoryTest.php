<?php

namespace App\Tests\Repository;

use App\Repository\UserRepository;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Liip\TestFixturesBundle\Services\DatabaseTools\AbstractDatabaseTool;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;

class UserRepositoryTest extends KernelTestCase
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
            __DIR__ . '/UserRepositoryTestFixtures.yaml'
        ]);

        $users = $this->myContainer->get(UserRepository::class)->count([]);

        $this->assertEquals(10, $users);
    }

    public function testFindAll()
    {
        $this->databaseTool->loadAliceFixture([
            __DIR__ . '/UserRepositoryTestFixtures.yaml'
        ]);

        $users = $this->myContainer->get(UserRepository::class)->findAll();

        $this->assertCount(10, $users);
        $this->assertNotCount(5, $users);
        $this->assertIsArray($users);
    }

    public function testFindBy()
    {
        $this->databaseTool->loadAliceFixture([
            __DIR__ . '/UserRepositoryTestFixtures.yaml'
        ]);

        $users = $this->myContainer->get(UserRepository::class)->findBy(['id' => 1]);

        $this->assertCount(1, $users);
        $this->assertNotCount(2, $users);
        $this->assertIsArray($users);
    }

    public function testFindOneBy()
    {
        $this->databaseTool->loadAliceFixture([
            __DIR__ . '/UserRepositoryTestFixtures.yaml'
        ]);

        $user = $this->myContainer->get(UserRepository::class)->findOneBy(['id' => 1]);

        $this->assertIsNotArray($user);
        $this->assertIsObject($user);
    }

    public function testUpgradePassword()
    {
        $this->databaseTool->loadAliceFixture([
            __DIR__ . '/UserRepositoryTestFixtures.yaml'
        ]);

        $users = $this->myContainer->get(UserRepository::class)->findAll();

        $passwordHasher = $this->myContainer->get('test_alias.security.password_hasher');

        foreach ($users as $user) {
            $oldUser = clone $user;
            $oldUser->setPassword($passwordHasher->hashPassword($oldUser, "fakePassword"));

            $this->myContainer->get(UserRepository::class)->upgradePassword(
                $user,
                $passwordHasher->hashPassword($oldUser, "fakePassword")
            );
            $this->assertNotEquals($oldUser->getPassword(), $user->getPassword());
            $this->assertIsInt($user->getId());
        }
    }
}
