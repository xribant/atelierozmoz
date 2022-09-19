<?php

namespace App\Entity;

use App\Repository\EventRegistrationRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: EventRegistrationRepository::class)]
class EventRegistration
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank(message: 'Veuillez entrer un prénom')]
    private $firstName;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank(message: 'Veuillez entrer un nom')]
    private $lastName;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank(message: 'Veuillez entrer un email')]
    #[Assert\Email(message: 'Veuillez entrer une adresse e-mail valide')]
    private $email;

    #[ORM\Column(type: 'datetime')]
    #[Assert\NotBlank(message: 'Veuillez entrer votre date de naissance')]
    private $born_at;

    #[ORM\Column(type: 'datetime')]
    private $created_at;

    #[ORM\ManyToOne(targetEntity: Event::class, inversedBy: 'eventRegistrations')]
    #[Assert\NotBlank(message: 'Veuillez choisir un atelier')]
    #[ORM\JoinColumn(nullable: false)]
    private $event;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank(message: 'Veuillez entrer une adresse')]
    private $address;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank(message: 'Veuillez entrer une ville')]
    private $city;

    #[ORM\Column(type: 'integer')]
    #[Assert\Type(type: 'integer', message: 'Veuillez entrer un code postal valide')]
    #[Assert\Range(
        min: 1000,
        max: 9992,
        notInRangeMessage: 'Veuillez entrer un code postal valide')]
    #[Assert\NotBlank(message: 'Veuillez entrer un code postal')]
    
    private $postal_code;

    #[ORM\Column(type: 'string', length: 255)]
    private $uid;

    public function __construct() {
        $this->created_at = new DateTime();
        $this->uid = uniqid();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

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

    public function getBornAt(): ?\DateTimeInterface
    {
        return $this->born_at;
    }

    public function setBornAt(?\DateTimeInterface $born_at): self
    {
        $this->born_at = $born_at;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getEvent(): ?Event
    {
        return $this->event;
    }

    public function setEvent(?Event $event): self
    {
        $this->event = $event;

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

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getPostalCode(): ?int
    {
        return $this->postal_code;
    }

    public function setPostalCode(int $postal_code): self
    {
        $this->postal_code = $postal_code;

        return $this;
    }

    public function getUid(): ?string
    {
        return $this->uid;
    }

    public function setUid(string $uid): self
    {
        $this->uid = $uid;

        return $this;
    }
}
