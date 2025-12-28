<?php

namespace App\Entity;

use App\Repository\CommandeComplementRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommandeComplementRepository::class)]
#[ORM\Table(name: "commande_complement")]
class CommandeComplement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Commande::class, inversedBy: 'commandeComplements')]
    #[ORM\JoinColumn(name: 'id_commande', referencedColumnName: 'id', nullable: false)]
    private ?Commande $commande = null;

    #[ORM\ManyToOne(targetEntity: Complement::class, inversedBy: 'commandeComplements')]
    #[ORM\JoinColumn(name: 'id_complement', referencedColumnName: 'id', nullable: false)]
    private ?Complement $complement = null;

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

    public function getComplement(): ?Complement
    {
        return $this->complement;
    }

    public function setComplement(?Complement $complement): static
    {
        $this->complement = $complement;
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
        return ($this->complement?->getPrix() ?? 0) * ($this->qte ?? 0);
    }
}
