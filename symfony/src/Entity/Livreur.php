<?php

namespace App\Entity;

use App\Repository\LivreurRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LivreurRepository::class)]
#[ORM\Table(name: "livreur")]
class Livreur
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $nom = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $prenom = null;

    #[ORM\Column(length: 30, nullable: true)]
    private ?string $telephone = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): static
    {
        $this->nom = $nom;
        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(?string $prenom): static
    {
        $this->prenom = $prenom;
        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(?string $telephone): static
    {
        if ($telephone !== null) {
            // Nettoyer : supprimer les espaces, tirets, parenthèses
            $cleaned = preg_replace('/[^0-9+]/', '', $telephone);
            
            // Si commence par 221, garder le format avec +
            if (strpos($cleaned, '221') === 0) {
                $telephone = '+' . $cleaned;
            } 
            // Si commence par 77, 78, 75 (opérateurs sénégalais), ajouter le code pays
            elseif (preg_match('/^(77|78|75|76)\d{7}$/', $cleaned)) {
                $telephone = '+221' . $cleaned;
            }
            // Sinon, garder tel quel si c'est valide
            elseif (!preg_match('/^\+?221[0-9]{9}$/', $cleaned) && !preg_match('/^[0-9]{10}$/', $cleaned)) {
                // Si ce n'est pas valide, on le met quand même (pour ne pas casser les données)
                $telephone = $cleaned ?: $telephone;
            } else {
                $telephone = $cleaned;
            }
        }
        
        $this->telephone = $telephone;
        return $this;
    }

    public function getNomComplet(): string
    {
        return ($this->nom ?? '') . ' ' . ($this->prenom ?? '');
    }
}
