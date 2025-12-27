<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'livraison')]
class Livraison
{
    const STATUS_ASSIGNEE = 'ASSIGNEE';
    const STATUS_EN_ROUTE = 'EN_ROUTE';
    const STATUS_LIVREE = 'LIVREE';
    const STATUS_ECHOUEE = 'ECHOUEE';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\OneToOne(targetEntity: Commande::class, inversedBy: 'livraison')]
    #[ORM\JoinColumn(name: 'commande_id', referencedColumnName: 'id')]
    private Commande $commande;

    #[ORM\ManyToOne(targetEntity: Livreur::class, inversedBy: 'livraisons')]
    #[ORM\JoinColumn(name: 'livreur_id', referencedColumnName: 'id', nullable: true)]
    private ?Livreur $livreur = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(name: 'zone_id', referencedColumnName: 'id')]
    private Zone $zone;

    #[ORM\Column(type: 'string', length: 50, options: ['default' => self::STATUS_ASSIGNEE])]
    private string $etat = self::STATUS_ASSIGNEE;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTime $dateAssignation = null;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTime $dateLivraison = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $notes = null;

    public function __construct()
    {
        $this->dateAssignation = new \DateTime();
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

    public function getLivreur(): ?Livreur
    {
        return $this->livreur;
    }

    public function setLivreur(?Livreur $livreur): self
    {
        $this->livreur = $livreur;
        return $this;
    }

    public function getZone(): Zone
    {
        return $this->zone;
    }

    public function setZone(Zone $zone): self
    {
        $this->zone = $zone;
        return $this;
    }

    public function getEtat(): string
    {
        return $this->etat;
    }

    public function setEtat(string $etat): self
    {
        $this->etat = $etat;
        return $this;
    }

    public function getDateAssignation(): ?\DateTime
    {
        return $this->dateAssignation;
    }

    public function setDateAssignation(?\DateTime $dateAssignation): self
    {
        $this->dateAssignation = $dateAssignation;
        return $this;
    }

    public function getDateLivraison(): ?\DateTime
    {
        return $this->dateLivraison;
    }

    public function setDateLivraison(?\DateTime $dateLivraison): self
    {
        $this->dateLivraison = $dateLivraison;
        return $this;
    }

    public function getNotes(): ?string
    {
        return $this->notes;
    }

    public function setNotes(?string $notes): self
    {
        $this->notes = $notes;
        return $this;
    }
}
