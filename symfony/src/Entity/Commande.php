<?php

namespace App\Entity;

use App\Repository\CommandeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommandeRepository::class)]
#[ORM\Table(name: "commande")]
class Commande
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $etat = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $mode = null;

    #[ORM\Column(nullable: true)]
    private ?float $montant = null;

    #[ORM\ManyToOne(targetEntity: Client::class, inversedBy: 'commandes')]
    #[ORM\JoinColumn(name: 'id_client', referencedColumnName: 'id', nullable: false)]
    private ?Client $client = null;

    #[ORM\OneToMany(mappedBy: 'commande', targetEntity: CommandeBurger::class, cascade: ['remove'])]
    private Collection $commandeBurgers;

    #[ORM\OneToMany(mappedBy: 'commande', targetEntity: CommandeMenu::class, cascade: ['remove'])]
    private Collection $commandeMenus;

    #[ORM\OneToMany(mappedBy: 'commande', targetEntity: CommandeComplement::class, cascade: ['remove'])]
    private Collection $commandeComplements;

    #[ORM\OneToOne(mappedBy: 'commande', targetEntity: Livraison::class, cascade: ['remove'])]
    private ?Livraison $livraison = null;

    #[ORM\OneToOne(mappedBy: 'commande', targetEntity: Paiement::class, cascade: ['remove'])]
    private ?Paiement $paiement = null;

    public function __construct()
    {
        $this->date = new \DateTime();
        $this->commandeBurgers = new ArrayCollection();
        $this->commandeMenus = new ArrayCollection();
        $this->commandeComplements = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(?\DateTimeInterface $date): static
    {
        $this->date = $date;
        return $this;
    }

    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(?string $etat): static
    {
        $this->etat = $etat;
        return $this;
    }

    public function getMode(): ?string
    {
        return $this->mode;
    }

    public function setMode(?string $mode): static
    {
        $this->mode = $mode;
        return $this;
    }

    public function getMontant(): ?float
    {
        return $this->montant;
    }

    public function setMontant(?float $montant): static
    {
        $this->montant = $montant;
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

    public function getCommandeBurgers(): Collection
    {
        return $this->commandeBurgers;
    }

    public function addCommandeBurger(CommandeBurger $commandeBurger): static
    {
        if (!$this->commandeBurgers->contains($commandeBurger)) {
            $this->commandeBurgers->add($commandeBurger);
            $commandeBurger->setCommande($this);
        }
        return $this;
    }

    public function removeCommandeBurger(CommandeBurger $commandeBurger): static
    {
        if ($this->commandeBurgers->removeElement($commandeBurger)) {
            if ($commandeBurger->getCommande() === $this) {
                $commandeBurger->setCommande(null);
            }
        }
        return $this;
    }

    public function getCommandeMenus(): Collection
    {
        return $this->commandeMenus;
    }

    public function addCommandeMenu(CommandeMenu $commandeMenu): static
    {
        if (!$this->commandeMenus->contains($commandeMenu)) {
            $this->commandeMenus->add($commandeMenu);
            $commandeMenu->setCommande($this);
        }
        return $this;
    }

    public function removeCommandeMenu(CommandeMenu $commandeMenu): static
    {
        if ($this->commandeMenus->removeElement($commandeMenu)) {
            if ($commandeMenu->getCommande() === $this) {
                $commandeMenu->setCommande(null);
            }
        }
        return $this;
    }

    public function getCommandeComplements(): Collection
    {
        return $this->commandeComplements;
    }

    public function addCommandeComplement(CommandeComplement $commandeComplement): static
    {
        if (!$this->commandeComplements->contains($commandeComplement)) {
            $this->commandeComplements->add($commandeComplement);
            $commandeComplement->setCommande($this);
        }
        return $this;
    }

    public function removeCommandeComplement(CommandeComplement $commandeComplement): static
    {
        if ($this->commandeComplements->removeElement($commandeComplement)) {
            if ($commandeComplement->getCommande() === $this) {
                $commandeComplement->setCommande(null);
            }
        }
        return $this;
    }

    public function getLivraison(): ?Livraison
    {
        return $this->livraison;
    }

    public function setLivraison(?Livraison $livraison): static
    {
        if ($livraison === null && $this->livraison !== null) {
            $this->livraison->setCommande(null);
        } elseif ($livraison !== null && $livraison->getCommande() !== $this) {
            $livraison->setCommande($this);
        }
        $this->livraison = $livraison;
        return $this;
    }

    public function getPaiement(): ?Paiement
    {
        return $this->paiement;
    }

    public function setPaiement(?Paiement $paiement): static
    {
        if ($paiement === null && $this->paiement !== null) {
            $this->paiement->setCommande(null);
        } elseif ($paiement !== null && $paiement->getCommande() !== $this) {
            $paiement->setCommande($this);
        }
        $this->paiement = $paiement;
        return $this;
    }
}
