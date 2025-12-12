<?php

namespace App\Entity;

use App\Repository\CommandeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommandeRepository::class)]
#[ORM\Table(name: 'commandes')]
class Commande
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 255)]
    private string $clientNom;

    #[ORM\Column(type: 'string', length: 255)]
    private string $clientPrenom;

    #[ORM\Column(type: 'string', length: 20, nullable: true)]
    private ?string $clientTelephone = null;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2)]
    private string $montantTotal;

    #[ORM\Column(type: 'string', length: 50)]
    private string $statut = 'EN_ATTENTE';

    #[ORM\Column(type: 'string', length: 50)]
    private string $typeCommande;

    #[ORM\Column(type: 'datetime')]
    private \DateTimeInterface $dateCreation;

    #[ORM\Column(type: 'datetime')]
    private \DateTimeInterface $dateModification;

    public function __construct()
    {
        $this->dateCreation = new \DateTime();
        $this->dateModification = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getClientNom(): string
    {
        return $this->clientNom;
    }

    public function setClientNom(string $clientNom): self
    {
        $this->clientNom = $clientNom;
        return $this;
    }

    public function getClientPrenom(): string
    {
        return $this->clientPrenom;
    }

    public function setClientPrenom(string $clientPrenom): self
    {
        $this->clientPrenom = $clientPrenom;
        return $this;
    }

    public function getClientTelephone(): ?string
    {
        return $this->clientTelephone;
    }

    public function setClientTelephone(?string $clientTelephone): self
    {
        $this->clientTelephone = $clientTelephone;
        return $this;
    }

    public function getMontantTotal(): string
    {
        return $this->montantTotal;
    }

    public function setMontantTotal(string $montantTotal): self
    {
        $this->montantTotal = $montantTotal;
        return $this;
    }

    public function getStatut(): string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): self
    {
        $this->statut = $statut;
        return $this;
    }

    public function getTypeCommande(): string
    {
        return $this->typeCommande;
    }

    public function setTypeCommande(string $typeCommande): self
    {
        $this->typeCommande = $typeCommande;
        return $this;
    }

    public function getDateCreation(): \DateTimeInterface
    {
        return $this->dateCreation;
    }

    public function setDateCreation(\DateTimeInterface $dateCreation): self
    {
        $this->dateCreation = $dateCreation;
        return $this;
    }

    public function getDateModification(): \DateTimeInterface
    {
        return $this->dateModification;
    }

    public function setDateModification(\DateTimeInterface $dateModification): self
    {
        $this->dateModification = $dateModification;
        return $this;
    }
}
