<?php

namespace App\Entity\Resource;

use App\Repository\PictureRepository;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Trait\Timestampable;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=PictureRepository::class)
 * @ORM\Table(name="`pictures`")
 * @ORM\HasLifecycleCallbacks()
 */
class Picture
{
    use Timestampable;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    private ?string $pictureUrl;

    /**
     * @ORM\ManyToOne(targetEntity=Product::class, inversedBy="pictures")
     */
    private ?Product $product;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPictureUrl(): ?string
    {
        return $this->pictureUrl;
    }

    public function setPictureUrl(string $pictureUrl): self
    {
        $this->pictureUrl = $pictureUrl;

        return $this;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): self
    {
        $this->product = $product;

        return $this;
    }
}
