<?php

namespace App\Tests\Entity\Resource;

use App\Entity\Resource\Customer;
use App\Entity\Resource\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserResourceTest extends KernelTestCase
{
    public function getEntity(): User
    {
        return (new User())
            ->setEmail("ngstars@some.where")
            ->setPassword("fakePass")
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
                ->setPassword("")
                ->setEmail("ng-stars"),
            2
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
        $this->assertIsObject($user->addCustomer($customer)->getCustomers());
        $this->assertCount(0, $user->removeCustomer($customer)->getCustomers());
        $this->assertEquals(null, $user->getSalt());
        $this->assertEquals($user->getEmail(), $user->getUserIdentifier());
    }
}
