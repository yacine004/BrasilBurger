<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'paiement')]
class Paiement
{
    const METHODE_WAVE = 'WAVE';
    const METHODE_OM = 'OM';
    const METHODE_ESPECE = 'ESPECE';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\OneToOne(targetEntity: Commande::class, inversedBy: 'paiement')]
    #[ORM\JoinColumn(name: 'commande_id', referencedColumnName: 'id')]
    private Commande $commande;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2)]
    private float $montant;

    #[ORM\Column(type: 'string', length: 50)]
    private string $methode;

    #[ORM\Column(type: 'datetime')]
    private \DateTime $datePaiement;

    public function __construct()
    {
        $this->datePaiement = new \DateTime();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getCommande(): Commande
    {
        return $this->commande;
    }

    public function setCommande(Commande $commande): self
    {
        $this->commande = $commande;
        return $this;
    }

    public function getMontant(): float
    {
        return $this->montant;
    }

    public function setMontant(float $montant): self
    {
        $this->montant = $montant;
        return $this;
    }

    public function getMethode(): string
    {
        return $this->methode;
    }

    public function setMethode(string $methode): self
    {
        $this->methode = $methode;
        return $this;
    }

    public function getDatePaiement(): \DateTime
    {
        return $this->datePaiement;
    }

    public function setDatePaiement(\DateTime $datePaiement): self
    {
        $this->datePaiement = $datePaiement;
        return $this;
    }
}
