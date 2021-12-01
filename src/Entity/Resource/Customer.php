<?php

namespace App\Entity\Resource;

use App\Repository\CustomerRepository;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Trait\Timestampable;
use App\Entity\Trait\Slug;

/**
 * @ORM\Entity(repositoryClass=CustomerRepository::class)
 * @ORM\Table(name="`customers`")
 * @ORM\HasLifecycleCallbacks()
 */
class Customer
{
    use Timestampable;
    use Slug;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $fullName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $type;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $address;

    /**
     * @ORM\Column(type="integer")
     */
    private ?int $zipCode;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private ?string $country;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private ?string $city;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $contact;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="customers")
     */
    private ?User $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    private function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getFullName(): ?string
    {
        return $this->fullName;
    }

    public function setFullName(string $fullName): self
    {
        $this->fullName = $fullName;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getZipCode(): ?int
    {
        return $this->zipCode;
    }

    public function setZipCode(int $zipCode): self
    {
        $this->zipCode = $zipCode;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getContact(): ?string
    {
        return $this->contact;
    }

    public function setContact(string $contact): self
    {
        $this->contact = $contact;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
