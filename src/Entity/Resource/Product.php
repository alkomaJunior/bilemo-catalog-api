<?php

namespace App\Entity\Resource;

use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Trait\Timestampable;
use App\Entity\Trait\Slug;
use JetBrains\PhpStorm\Pure;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;
use Hateoas\Configuration\Annotation as Hateoas;

/**
 * @ORM\Entity(repositoryClass=ProductRepository::class)
 * @ORM\Table(name="`products`")
 * @ORM\HasLifecycleCallbacks()
 * @Hateoas\Relation(
 *      "self",
 *      href = @Hateoas\Route(
 *          "api_product_show",
 *          parameters = { "id" = "expr(object.getId())" },
 *          absolute = true
 *      )
 * )
 * @Hateoas\Relation(
 *      "list",
 *      href = @Hateoas\Route(
 *          "api_product_list",
 *          absolute = true
 *      )
 * )
 */
class Product
{
    use Slug;
    use Timestampable;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Serializer\Groups({ "showProduct", "listProduct" })
     */
    private ?int $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     * @Serializer\Groups({ "showProduct", "listProduct" })
     */
    private ?string $name;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     * @Serializer\Groups({ "showProduct", "listProduct" })
     */
    private ?string $price;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank()
     * @Serializer\Groups({ "showProduct" })
     */
    private ?string $description;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     * @Serializer\Groups({ "showProduct", "listProduct" })
     */
    private ?string $brand;

    /**
     * @ORM\OneToMany(targetEntity=Picture::class, mappedBy="product")
     * @Serializer\Groups({ "showProduct" })
     */
    private Collection $pictures;

    #[Pure]
    public function __construct()
    {
        $this->pictures = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(string $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getBrand(): ?string
    {
        return $this->brand;
    }

    public function setBrand(string $brand): self
    {
        $this->brand = $brand;

        return $this;
    }

    /**
     * @return Collection
     */
    public function getPictures(): Collection
    {
        return $this->pictures;
    }

    public function addPicture(Picture $picture): self
    {
        if (!$this->pictures->contains($picture)) {
            $this->pictures[] = $picture;
            $picture->setProduct($this);
        }

        return $this;
    }

    public function removePicture(Picture $picture): self
    {
        if ($this->pictures->removeElement($picture)) {
            // set the owning side to null (unless already changed)
            if ($picture->getProduct() === $this) {
                $picture->setProduct(null);
            }
        }

        return $this;
    }
}
