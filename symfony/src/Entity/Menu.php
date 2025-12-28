<?php

namespace App\Entity;

use App\Repository\MenuRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MenuRepository::class)]
#[ORM\Table(name: "menu")]
class Menu
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $nom = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image = null;

    #[ORM\ManyToMany(targetEntity: Burger::class, inversedBy: 'menus')]
    #[ORM\JoinTable(name: "menu_burger", joinColumns: [new ORM\JoinColumn(name: 'id_menu', referencedColumnName: 'id')], inverseJoinColumns: [new ORM\JoinColumn(name: 'id_burger', referencedColumnName: 'id')])]
    private Collection $burgers;

    #[ORM\OneToMany(mappedBy: 'menu', targetEntity: CommandeMenu::class, cascade: ['remove'])]
    private Collection $commandeMenus;

    public function __construct()
    {
        $this->burgers = new ArrayCollection();
        $this->commandeMenus = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;
        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): static
    {
        $this->image = $image;
        return $this;
    }

    public function getBurgers(): Collection
    {
        return $this->burgers;
    }

    public function addBurger(Burger $burger): static
    {
        if (!$this->burgers->contains($burger)) {
            $this->burgers->add($burger);
        }
        return $this;
    }

    public function removeBurger(Burger $burger): static
    {
        $this->burgers->removeElement($burger);
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
            $commandeMenu->setMenu($this);
        }
        return $this;
    }

    public function removeCommandeMenu(CommandeMenu $commandeMenu): static
    {
        if ($this->commandeMenus->removeElement($commandeMenu)) {
            if ($commandeMenu->getMenu() === $this) {
                $commandeMenu->setMenu(null);
            }
        }
        return $this;
    }

    /**
     * Calcule le prix total du menu en sommant les prix des burgers associés
     */
    public function getPrixTotal(): float
    {
        $total = 0;
        foreach ($this->burgers as $burger) {
            $total += $burger->getPrix();
        }
        return $total;
    }
}
