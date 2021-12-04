<?php

namespace App\Tests\Entity\Resource;

use App\Entity\Resource\Customer;
use App\Entity\Resource\User;
use App\Repository\CustomerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserResourceTest extends KernelTestCase
{
    public function getEntity(): User
    {
        return (new User())
            ->setEmail("ngstars@some.where")
            ->setPassword("fakePass")
            ->setSlug("enterprise")
            ->setCreatedAt(new \DateTimeImmutable())
            ->setUpdatedAt(new \DateTimeImmutable())
        ;
    }

    public function assertHasErrors(User $user, int $number = 0)
    {
        self::bootKernel();
        $validator = static::getContainer()->get(ValidatorInterface::class);
        $error = $validator->validate($user);
        $this->assertCount($number, $error);
    }

    public function testValidUserEntity()
    {
        $this->assertHasErrors($this->getEntity(), 0);
    }

    public function testInvalidUserEntity()
    {
        $this->assertHasErrors(
            $this->getEntity()
                ->setPassword("aze"),
            1
        );
    }

    public function testGettersSetters()
    {
        $user = $this->getEntity();

        $customer = (new Customer())
            ->setFullName("NG-STARs")
            ->setEmail("ngstars@some.where")
            ->setAddress("BE")
            ->setCity("LOME")
            ->setCountry("TOGO")
            ->setZipCode(60995)
            ->setContact("+22899556688")
            ->setType("Enterprise")
        ;

        $this->assertIsString($user->setEmail("entreprise@some.where")->getUsername());
        $this->assertIsString($user->setPassword("fakePass")->getEmail());
        $this->assertIsArray($user->setRoles(["ROLE_CUSTOMER_USER"])->getRoles());
        $this->assertEquals(null, $user->getSalt());
        $this->assertEquals($user->getEmail(), $user->getUserIdentifier());
        $this->assertIsObject($user->setCreatedAt(new \DateTimeImmutable())->getCreatedAt());
        $this->assertIsObject($user->setUpdatedAt(new \DateTimeImmutable())->getUpdatedAt());
        $this->assertIsObject($user->setCustomer($customer)->getCustomer());
        $this->assertIsString($user->setSlug("enterprise")->getSlug());
        $this->assertNull($user->eraseCredentials());
    }
}
