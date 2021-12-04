<?php

namespace App\Tests\Entity\Resource;

use App\Entity\Resource\Picture;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class PictureTestCaseTest extends KernelTestCase
{
    public function getEntity(): Picture
    {
        return (new Picture())
            ->setPictureUrl("https://www.w3schools.com/images/w3schools_logo_500_04AA6D.png")
            ->setCreatedAt(new \DateTimeImmutable())
            ->setUpdatedAt(new \DateTimeImmutable())
            ;
    }

    public function assertHasErrors(Picture $picture, int $number = 0)
    {
        self::bootKernel();
        $validator = static::getContainer()->get(ValidatorInterface::class);
        $error = $validator->validate($picture);
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
                ->setPictureUrl(""),
            1
        );
    }

    public function testGettersSetters()
    {
        $picture = $this->getEntity();

        $this->assertIsString($picture->setPictureUrl("https://www.w3schools.com/images/w3schools_logo_500_04AA6D.png")->getPictureUrl());
        $this->assertIsObject($picture->setProduct((new ProductResourceTest())->getEntity())->getProduct());
    }
}
