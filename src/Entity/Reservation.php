<?php

namespace App\Entity;

use App\Repository\ReservationRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Symfony\Component\Validator\Constraints as Assert;


#[HasLifecycleCallbacks]
#[ORM\Entity(repositoryClass: ReservationRepository::class)]
class Reservation
{
    const STATUS_PENDING = 'PENDING';
    const STATUS_CONFIRMED = 'CONFIRMED';
    const STATUS_CANCELLED = 'CANCELLED';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 5, max: 255 , minMessage: 'L\'adresse de départ doit contenir au moins 5 caractères' , maxMessage: 'L\'adresse de départ doit contenir au maximum 255 caractères')]
    private ?string $depAddress = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 5, max: 255 , minMessage: 'L\'adresse de destination doit contenir au moins 5 caractères' , maxMessage: 'L\'adresse de destination doit contenir au maximum 255 caractères')]

    private ?string $destination = null;

    #[ORM\Column]
    #[Assert\NotBlank]
    
    private ?\DateTimeImmutable $reservation_datetime = null;


    
    #[ORM\Column]
    private ?string $status = null;

    #[ORM\Column(nullable: true)]
    private ?float $price = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\ManyToOne(inversedBy: 'reservations')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Client $client = null;

    #[ORM\Column]
    #[Assert\Range(min: 1, max: 4 , notInRangeMessage: 'Le nombre de passagers doit être compris entre 1 et 4')]
    private ?int $nbPassengers = null;


    #[ORM\PrePersist]
    public function PrePersist(){
        $this->createdAt = new \DateTimeImmutable();
        $this->status = $this::STATUS_PENDING;
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDepAddress(): ?string
    {
        return $this->depAddress;
    }

    public function setDepAddress(string $depAddress): static
    {
        $this->depAddress = $depAddress;

        return $this;
    }

    public function getDestination(): ?string
    {
        return $this->destination;
    }

    public function setDestination(string $destination): static
    {
        $this->destination = $destination;

        return $this;
    }


    public function getReservationDatetime(): ?\DateTimeImmutable
    {
        return $this->reservation_datetime;
    }

    public function setReservationDatetime(\DateTimeImmutable $reservation_datetime): static
    {
        $this->reservation_datetime = $reservation_datetime;

        return $this;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(?float $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): static
    {
        $this->client = $client;

        return $this;
    }

    public function getNbPassengers(): ?int
    {
        return $this->nbPassengers;
    }

    public function setNbPassengers(int $nbPassengers): static
    {
        $this->nbPassengers = $nbPassengers;

        return $this;
    }

}
