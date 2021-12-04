<?php

namespace App\Tests\Entity\Resource;

use App\Entity\Resource\Picture;
use App\Entity\Resource\Product;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ProductResourceTest extends KernelTestCase
{
    public function getEntity(): Product
    {
        return (new Product())
            ->setName("Galaxy 5")
            ->setPrice(12345)
            ->setDescription("Product description")
            ->setBrand("Samsung")
            ->setSlug("galaxy5")
            ->setCreatedAt(new \DateTimeImmutable())
            ->setUpdatedAt(new \DateTimeImmutable())
            ;
    }

    public function assertHasErrors(Product $product, int $number = 0)
    {
        self::bootKernel();
        $validator = static::getContainer()->get(ValidatorInterface::class);
        $error = $validator->validate($product);
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
                ->setBrand(""),
            1
        );
    }

    public function testGettersSetters()
    {
        $product = $this->getEntity();

        $pictures = [(new PictureTestCaseTest())->getEntity()];

        $this->assertIsString($product->setName("Galaxy 5")->getName());
        $this->assertIsFloat($product->setPrice(12345)->getPrice());
        $this->assertIsString($product->setDescription("Product description")->getDescription());
        $this->assertIsString($product->setBrand("Samsung")->getBrand());
        $this->assertIsString($product->setSlug("galaxy")->getSlug());
        $this->assertIsObject($product->addPicture($pictures[0])->removePicture($pictures[0]));
        $this->assertCount(0, $product->removePicture($pictures[0])->getPictures());
    }
}
