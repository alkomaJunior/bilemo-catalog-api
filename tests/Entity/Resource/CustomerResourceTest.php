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
            ->setSlug("stars")
            ->setCreatedAt(new \DateTimeImmutable())
            ->setUpdatedAt(new \DateTimeImmutable())
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
                ->setCity(""),
            1,
        );
    }

    public function testGettersSetters()
    {
        $customer = $this->getEntity();
        $users = [
            (new User())
                ->setEmail("ngstars@some.where")
                ->setPassword("fakePass"),
            (new User())
                ->setEmail("logo@some.where")
                ->setPassword("fakePass")
        ];

        $this->assertIsString($customer->setEmail("entreprise@some.where")->getEmail());
        $this->assertIsString($customer->setFullName("cusName")->getFullName());
        $this->assertIsString($customer->setAddress("cusAddress")->getAddress());
        $this->assertIsString($customer->setCity("cusCity")->getCity());
        $this->assertIsString($customer->setCountry("cusCountry")->getCountry());
        $this->assertIsString($customer->setSlug("stars")->getSlug());
        $this->assertIsNotString($customer->setZipCode(12345)->getZipCode());
        $this->assertIsString($customer->setType("cusType")->getType());
        $this->assertIsString($customer->setContact("cusContact")->getContact());
        $this->assertIsObject($customer->addUser($users[1])->removeUser($users[1]));
        $this->assertCount(0, $customer->removeUser($users[0])->getUsers());
        $this->assertIsObject($customer->setCreatedAt(new \DateTimeImmutable())->getCreatedAt());
        $this->assertIsObject($customer->setUpdatedAt(new \DateTimeImmutable())->getUpdatedAt());
    }
}
