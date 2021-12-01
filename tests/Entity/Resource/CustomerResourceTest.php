<?php

namespace App\Tests\Entity\Resource;

use App\Entity\Resource\Customer;
use App\Entity\Resource\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CustomerResourceTest extends KernelTestCase
{
    public function getEntity(): Customer
    {
        return (new Customer())
            ->setFullName("NG-STARs")
            ->setEmail("ngstars@some.where")
            ->setAddress("BE")
            ->setCity("LOME")
            ->setCountry("TOGO")
            ->setZipCode(60995)
            ->setContact("+22899556688")
            ->setType("Enterprise")
        ;
    }

    public function assertHasErrors(Customer $customer, int $number = 0)
    {
        self::bootKernel();
        $validator = static::getContainer()->get(ValidatorInterface::class);
        $error = $validator->validate($customer);
        $this->assertCount($number, $error);
    }

    public function testValidCustomerEntity()
    {
        $this->assertHasErrors($this->getEntity(), 0);
    }

    public function testInvalidCustomerEntity()
    {
        $this->assertHasErrors(
            $this->getEntity()
                ->setCity("")
                ->setEmail("ng-stars")
                ->setZipCode(-123),
            3,
        );
    }

    public function testGettersSetters()
    {
        $customer = $this->getEntity();
        $user = (new User())
            ->setEmail("ngstars@some.where")
            ->setPassword("fakePass")
        ;

        $this->assertIsString($customer->setEmail("entreprise@some.where")->getEmail());
        $this->assertIsString($customer->setFullName("cusName")->getFullName());
        $this->assertIsString($customer->setAddress("cusAddress")->getAddress());
        $this->assertIsString($customer->setCity("cusCity")->getCity());
        $this->assertIsString($customer->setCountry("cusCountry")->getCountry());
        $this->assertIsNotString($customer->setZipCode(12345)->getZipCode());
        $this->assertIsString($customer->setType("cusType")->getType());
        $this->assertIsString($customer->setContact("cusContact")->getContact());
        $this->assertIsObject($customer->setUser($user)->getUser());
    }
}
