<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity]
#[ORM\Table(name: 'commande')]
class Commande
{
    const STATUS_EN_ATTENTE = 'EN_ATTENTE';
    const STATUS_CONFIRMEE = 'CONFIRMEE';
    const STATUS_EN_COURS = 'EN_COURS';
    const STATUS_TERMINER = 'TERMINER';
    const STATUS_LIVREE = 'LIVREE';
    const STATUS_ANNULEE = 'ANNULEE';

    const TYPE_PLACE = 'PLACE';
    const TYPE_RETRAIT = 'RETRAIT';
    const TYPE_LIVRAISON = 'LIVRAISON';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(name: 'client_id', referencedColumnName: 'id')]
    private Client $client;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2)]
    private float $montant;

    #[ORM\Column(type: 'string', length: 50, options: ['default' => self::STATUS_EN_ATTENTE])]
    private string $etat = self::STATUS_EN_ATTENTE;

    #[ORM\Column(type: 'string', length: 50, options: ['default' => self::TYPE_PLACE])]
    private string $type = self::TYPE_PLACE;

    #[ORM\Column(type: 'datetime')]
    private \DateTime $dateCommande;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTime $dateTerminaison = null;

    #[ORM\Column(type: 'boolean', options: ['default' => false])]
    private bool $payee = false;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $notes = null;

    #[ORM\OneToMany(targetEntity: CommandeBurger::class, mappedBy: 'commande', cascade: ['persist', 'remove'])]
    private Collection $burgers;

    #[ORM\OneToMany(targetEntity: CommandeMenu::class, mappedBy: 'commande', cascade: ['persist', 'remove'])]
    private Collection $menus;

    #[ORM\OneToMany(targetEntity: CommandeComplement::class, mappedBy: 'commande', cascade: ['persist', 'remove'])]
    private Collection $complements;

    #[ORM\OneToOne(targetEntity: Paiement::class, mappedBy: 'commande', cascade: ['persist', 'remove'])]
    private ?Paiement $paiement = null;

    #[ORM\OneToOne(targetEntity: Livraison::class, mappedBy: 'commande', cascade: ['persist', 'remove'])]
    private ?Livraison $livraison = null;

    public function __construct()
    {
        $this->dateCommande = new \DateTime();
        $this->burgers = new ArrayCollection();
        $this->menus = new ArrayCollection();
        $this->complements = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getClient(): Client
    {
        return $this->client;
    }

    public function setClient(Client $client): self
    {
        $this->client = $client;
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

    public function getEtat(): string
    {
        return $this->etat;
    }

    public function setEtat(string $etat): self
    {
        $this->etat = $etat;
        return $this;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;
        return $this;
    }

    public function getDateCommande(): \DateTime
    {
        return $this->dateCommande;
    }

    public function setDateCommande(\DateTime $dateCommande): self
    {
        $this->dateCommande = $dateCommande;
        return $this;
    }

    public function getDateTerminaison(): ?\DateTime
    {
        return $this->dateTerminaison;
    }

    public function setDateTerminaison(?\DateTime $dateTerminaison): self
    {
        $this->dateTerminaison = $dateTerminaison;
        return $this;
    }

    public function isPayee(): bool
    {
        return $this->payee;
    }

    public function setPayee(bool $payee): self
    {
        $this->payee = $payee;
        return $this;
    }

    public function getBurgers(): Collection
    {
        return $this->burgers;
    }

    public function getMenus(): Collection
    {
        return $this->menus;
    }

    public function getComplements(): Collection
    {
        return $this->complements;
    }

    public function getPaiement(): ?Paiement
    {
        return $this->paiement;
    }

    public function setPaiement(?Paiement $paiement): self
    {
        $this->paiement = $paiement;
        return $this;
    }

    public function getLivraison(): ?Livraison
    {
        return $this->livraison;
    }

    public function setLivraison(?Livraison $livraison): self
    {
        $this->livraison = $livraison;
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
