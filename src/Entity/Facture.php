<?php

namespace App\Entity;

use App\Repository\FactureRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;

#[HasLifecycleCallbacks]
#[ORM\Entity(repositoryClass: FactureRepository::class)]
class Facture
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column()]
    private ?float $priceHT = null;

    #[ORM\Column()]
    private ?float $PriceTTC = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(length: 255,nullable: true)]
    private ?string $name = null;


    #[ORM\ManyToOne(inversedBy: 'factures')]
    private ?Client $client = null;

    #[ORM\OneToOne(mappedBy: 'facture', cascade: ['persist', 'remove'])]
    private ?Reservation $reservation = null;




    #[ORM\PrePersist]
    public function prePersist(): void
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->priceHT = $this->PriceTTC / 1.2;
    }

    public function getId(): ?int
    {
        return $this->id;
    }



    public function getPriceHT(): ?float
    {
        return $this->priceHT;
    }

    public function setPriceHT(float $priceHT): static
    {
        $this->priceHT = $priceHT;


        return $this;
    }

    public function getPriceTTC(): ?float
    {
        return $this->PriceTTC;
    }

    public function setPriceTTC(float $PriceTTC): static
    {
        $this->PriceTTC = $PriceTTC;

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

    public function __toString(): string
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

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

    public function getReservation(): ?Reservation
    {
        return $this->reservation;
    }

    public function setReservation(?Reservation $reservation): static
    {
        // unset the owning side of the relation if necessary
        if ($reservation === null && $this->reservation !== null) {
            $this->reservation->setFacture(null);
        }

        // set the owning side of the relation if necessary
        if ($reservation !== null && $reservation->getFacture() !== $this) {
            $reservation->setFacture($this);
        }

        $this->reservation = $reservation;

        return $this;
    }

}
