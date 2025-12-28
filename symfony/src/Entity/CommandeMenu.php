<?php

namespace App\Entity;

use App\Repository\CommandeMenuRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommandeMenuRepository::class)]
#[ORM\Table(name: "commande_menu")]
class CommandeMenu
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Commande::class, inversedBy: 'commandeMenus')]
    #[ORM\JoinColumn(name: 'id_commande', referencedColumnName: 'id', nullable: false)]
    private ?Commande $commande = null;

    #[ORM\ManyToOne(targetEntity: Menu::class, inversedBy: 'commandeMenus')]
    #[ORM\JoinColumn(name: 'id_menu', referencedColumnName: 'id', nullable: false)]
    private ?Menu $menu = null;

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

    public function getMenu(): ?Menu
    {
        return $this->menu;
    }

    public function setMenu(?Menu $menu): static
    {
        $this->menu = $menu;
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
        return ($this->menu?->getPrixTotal() ?? 0) * ($this->qte ?? 0);
    }
}
