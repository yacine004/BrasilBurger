<?php

namespace App\Service;

use App\Entity\Commande;
use App\Entity\CommandeBurger;
use App\Entity\CommandeMenu;
use App\Entity\CommandeComplement;
use App\Repository\BurgerRepository;
use App\Repository\MenuRepository;
use App\Repository\ComplementRepository;
use Doctrine\ORM\EntityManagerInterface;

class CommandeService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private BurgerRepository $burgerRepository,
        private MenuRepository $menuRepository,
        private ComplementRepository $complementRepository
    ) {}

    /**
     * Crée une nouvelle commande
     */
    public function createCommande(Commande $commande): void
    {
        $commande->setEtat('En attente de paiement');
        if ($commande->getDate() === null) {
            $commande->setDate(new \DateTime());
        }

        $this->entityManager->persist($commande);
        $this->entityManager->flush();
    }

    /**
     * Ajoute un burger à une commande
     */
    public function addBurgerToCommande(Commande $commande, int $burgerId, int $qte): void
    {
        $burger = $this->burgerRepository->find($burgerId);
        if (!$burger) {
            throw new \Exception('Burger not found');
        }

        $commandeBurger = new CommandeBurger();
        $commandeBurger->setCommande($commande);
        $commandeBurger->setBurger($burger);
        $commandeBurger->setQte($qte);

        $this->entityManager->persist($commandeBurger);
        $this->calculateAndSetTotal($commande);
        $this->entityManager->flush();
    }

    /**
     * Ajoute un menu à une commande
     */
    public function addMenuToCommande(Commande $commande, int $menuId, int $qte): void
    {
        $menu = $this->menuRepository->find($menuId);
        if (!$menu) {
            throw new \Exception('Menu not found');
        }

        $commandeMenu = new CommandeMenu();
        $commandeMenu->setCommande($commande);
        $commandeMenu->setMenu($menu);
        $commandeMenu->setQte($qte);

        $this->entityManager->persist($commandeMenu);
        $this->calculateAndSetTotal($commande);
        $this->entityManager->flush();
    }

    /**
     * Ajoute un complément à une commande
     */
    public function addComplementToCommande(Commande $commande, int $complementId, int $qte): void
    {
        $complement = $this->complementRepository->find($complementId);
        if (!$complement) {
            throw new \Exception('Complement not found');
        }

        $commandeComplement = new CommandeComplement();
        $commandeComplement->setCommande($commande);
        $commandeComplement->setComplement($complement);
        $commandeComplement->setQte($qte);

        $this->entityManager->persist($commandeComplement);
        $this->calculateAndSetTotal($commande);
        $this->entityManager->flush();
    }

    /**
     * Marque une commande comme validée
     */
    public function validateCommande(Commande $commande): void
    {
        $commande->setEtat('Validée');
        $this->entityManager->flush();
    }

    /**
     * Marque une commande comme prête (terminée)
     */
    public function markAsReady(Commande $commande): void
    {
        $commande->setEtat('Terminer');
        $this->entityManager->flush();
    }

    /**
     * Annule une commande
     */
    public function cancelCommande(Commande $commande): void
    {
        $commande->setEtat('Annulée');
        $this->entityManager->flush();
    }

    /**
     * Change le statut d'une commande
     */
    public function updateCommandeStatus(Commande $commande, string $status): void
    {
        $commande->setEtat($status);
        $this->entityManager->flush();
    }

    /**
     * Calcule et définit le montant total d'une commande
     */
    public function calculateAndSetTotal(Commande $commande): void
    {
        $total = 0;

        // Somme des burgers
        foreach ($commande->getCommandeBurgers() as $cb) {
            $total += $cb->getTotal();
        }

        // Somme des menus
        foreach ($commande->getCommandeMenus() as $cm) {
            $total += $cm->getTotal();
        }

        // Somme des compléments
        foreach ($commande->getCommandeComplements() as $cc) {
            $total += $cc->getTotal();
        }

        $commande->setMontant($total);
    }

    /**
     * Supprime une commande
     */
    public function deleteCommande(Commande $commande): void
    {
        $this->entityManager->remove($commande);
        $this->entityManager->flush();
    }

    /**
     * Récupère les détails d'une commande
     */
    public function getCommandeDetails(Commande $commande): array
    {
        return [
            'id' => $commande->getId(),
            'date' => $commande->getDate(),
            'etat' => $commande->getEtat(),
            'mode' => $commande->getMode(),
            'montant' => $commande->getMontant(),
            'client' => [
                'id' => $commande->getClient()?->getId(),
                'nom' => $commande->getClient()?->getNom(),
                'prenom' => $commande->getClient()?->getPrenom(),
                'telephone' => $commande->getClient()?->getTelephone(),
            ],
            'burgers' => $commande->getCommandeBurgers()->toArray(),
            'menus' => $commande->getCommandeMenus()->toArray(),
            'complements' => $commande->getCommandeComplements()->toArray(),
            'livraison' => $commande->getLivraison(),
            'paiement' => $commande->getPaiement(),
        ];
    }
}
