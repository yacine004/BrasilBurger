<?php

namespace App\Entity;

use App\Repository\CommandeBurgerRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommandeBurgerRepository::class)]
#[ORM\Table(name: "commande_burger")]
class CommandeBurger
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Commande::class, inversedBy: 'commandeBurgers')]
    #[ORM\JoinColumn(name: 'id_commande', referencedColumnName: 'id', nullable: false)]
    private ?Commande $commande = null;

    #[ORM\ManyToOne(targetEntity: Burger::class, inversedBy: 'commandeBurgers')]
    #[ORM\JoinColumn(name: 'id_burger', referencedColumnName: 'id', nullable: false)]
    private ?Burger $burger = null;

    #[ORM\Column]
    private ?int $qte = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCommande(): ?Commande
    {
        return $this->commande;
    }

    public function setCommande(?Commande $commande): static
    {
        $this->commande = $commande;
        return $this;
    }

    public function getBurger(): ?Burger
    {
        return $this->burger;
    }

    public function setBurger(?Burger $burger): static
    {
        $this->burger = $burger;
        return $this;
    }

    public function getQte(): ?int
    {
        return $this->qte;
    }

    public function setQte(int $qte): static
    {
        $this->qte = $qte;
        return $this;
    }

    public function getTotal(): float
    {
        return ($this->burger?->getPrix() ?? 0) * ($this->qte ?? 0);
    }
}
