<?php

namespace App\Entity\Trait;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

trait Slug
{
    /**
     * @ORM\Column(type="string", length=255)
     * @Serializer\Groups({ "showUser" })
     */
    private ?string $slug;

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }
}
