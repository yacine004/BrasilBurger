<?php

namespace App\Service;

use App\Entity\Commande;
use App\Entity\CommandeBurger;
use App\Entity\CommandeMenu;
use App\Entity\CommandeComplement;
use App\Entity\Paiement;
use App\Repository\CommandeRepository;
use App\Repository\ClientRepository;
use App\Repository\BurgerRepository;
use App\Repository\MenuRepository;
use Doctrine\ORM\EntityManagerInterface;

class CommandeService
{
    public function __construct(
        private CommandeRepository $commandeRepository,
        private ClientRepository $clientRepository,
        private BurgerRepository $burgerRepository,
        private MenuRepository $menuRepository,
        private EntityManagerInterface $em
    ) {}

    /**
     * Créer une nouvelle commande
     */
    public function creerCommande(int $clientId, string $type = Commande::TYPE_PLACE): Commande
    {
        $client = $this->clientRepository->find($clientId);
        if (!$client) {
            throw new \Exception('Client non trouvé');
        }

        $commande = new Commande();
        $commande->setClient($client);
        $commande->setType($type);
        $commande->setEtat(Commande::STATUS_EN_ATTENTE);

        return $commande;
    }

    /**
     * Ajouter un burger à la commande
     */
    public function ajouterBurger(Commande $commande, int $burgerId, int $quantite = 1): void
    {
        $burger = $this->burgerRepository->find($burgerId);
        if (!$burger) {
            throw new \InvalidArgumentException('Burger non trouvé');
        }

        $commandeBurger = new CommandeBurger();
        $commandeBurger->setBurger($burger);
        $commandeBurger->setQuantite($quantite);
        $commandeBurger->setPrixUnitaire($burger->getPrix());
        $commandeBurger->setCommande($commande);

        $commande->getBurgers()->add($commandeBurger);
        $this->mettreAJourMontant($commande);
    }

    /**
     * Ajouter un menu à la commande
     */
    public function ajouterMenu(Commande $commande, int $menuId, int $quantite = 1): void
    {
        $menu = $this->menuRepository->find($menuId);
        if (!$menu) {
            throw new \InvalidArgumentException('Menu non trouvé');
        }

        $commandeMenu = new CommandeMenu();
        $commandeMenu->setMenu($menu);
        $commandeMenu->setQuantite($quantite);
        $commandeMenu->setPrixUnitaire($menu->getPrix());
        $commandeMenu->setCommande($commande);

        $commande->getMenus()->add($commandeMenu);
        $this->mettreAJourMontant($commande);
    }

    /**
     * Ajouter un complément à la commande
     */
    public function ajouterComplement(Commande $commande, int $complementId, int $quantite = 1): void
    {
        $complement = $this->em->getRepository('App\Entity\Complement')->find($complementId);
        if (!$complement) {
            throw new \InvalidArgumentException('Complément non trouvé');
        }

        $commandeComplement = new CommandeComplement();
        $commandeComplement->setComplement($complement);
        $commandeComplement->setQuantite($quantite);
        $commandeComplement->setPrixUnitaire($complement->getPrix());
        $commandeComplement->setCommande($commande);

        $commande->getComplements()->add($commandeComplement);
        $this->mettreAJourMontant($commande);
    }

    /**
     * Mettre à jour le montant total de la commande
     */
    public function mettreAJourMontant(Commande $commande): void
    {
        $montant = 0;

        foreach ($commande->getBurgers() as $burger) {
            $montant += $burger->getPrixUnitaire() * $burger->getQuantite();
        }

        foreach ($commande->getMenus() as $menu) {
            $montant += $menu->getPrixUnitaire() * $menu->getQuantite();
        }

        foreach ($commande->getComplements() as $complement) {
            $montant += $complement->getPrixUnitaire() * $complement->getQuantite();
        }

        $commande->setMontant($montant);
    }

    /**
     * Confirmer une commande
     */
    public function confirmerCommande(Commande $commande): void
    {
        $commande->setEtat(Commande::STATUS_CONFIRMEE);
        $this->em->persist($commande);
        $this->em->flush();
    }

    /**
     * Annuler une commande
     */
    public function annulerCommande(Commande $commande): void
    {
        $commande->setEtat(Commande::STATUS_ANNULEE);
        $this->em->persist($commande);
        $this->em->flush();
    }

    /**
     * Terminer une commande
     */
    public function terminerCommande(Commande $commande): void
    {
        $commande->setEtat(Commande::STATUS_TERMINER);
        $this->em->persist($commande);
        $this->em->flush();
    }

    /**
     * Enregistrer le paiement d'une commande
     */
    public function effectuerPaiement(Commande $commande, float $montant, string $methode): Paiement
    {
        if ($montant != $commande->getMontant()) {
            throw new \InvalidArgumentException('Le montant du paiement ne correspond pas au total de la commande');
        }

        $paiement = new Paiement();
        $paiement->setCommande($commande);
        $paiement->setMontant($montant);
        $paiement->setMethode($methode);
        $paiement->setDatePaiement(new \DateTime());

        $commande->setPaiement($paiement);
        $commande->setPayee(true);

        $this->em->persist($paiement);
        $this->em->persist($commande);
        $this->em->flush();

        return $paiement;
    }

    /**
     * Récupérer les commandes d'un client
     */
    public function getCommandesClient(int $clientId): array
    {
        return $this->commandeRepository->findByClient($clientId);
    }

    /**
     * Récupérer une commande par ID
     */
    public function getCommande(int $commandeId): ?Commande
    {
        return $this->commandeRepository->find($commandeId);
    }
}
