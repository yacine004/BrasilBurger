<?php

namespace App\Service;

use App\Entity\Livraison;
use App\Entity\Commande;
use App\Entity\Livreur;
use App\Repository\LivreurRepository;
use Doctrine\ORM\EntityManagerInterface;

class LivraisonService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private LivreurRepository $livreurRepository
    ) {}

    /**
     * Crée une livraison pour une commande
     */
    public function createLivraison(Commande $commande, int $livreurId): Livraison
    {
        $livreur = $this->livreurRepository->find($livreurId);
        if (!$livreur) {
            throw new \Exception('Livreur not found');
        }

        $livraison = new Livraison();
        $livraison->setCommande($commande);
        $livraison->setLivreur($livreur);

        $this->entityManager->persist($livraison);
        $this->entityManager->flush();

        return $livraison;
    }

    /**
     * Assigne une commande de livraison à un livreur
     */
    public function assignToDelivery(Commande $commande, Livreur $livreur): void
    {
        if ($commande->getMode() === 'Livraison') {
            $livraison = new Livraison();
            $livraison->setCommande($commande);
            $livraison->setLivreur($livreur);

            $this->entityManager->persist($livraison);
            $this->entityManager->flush();
        }
    }

    /**
     * Récupère les livraisons du jour
     */
    public function getTodayDeliveries(): array
    {
        return $this->entityManager->createQuery('
            SELECT l FROM App\Entity\Livraison l
            JOIN l.commande c
            WHERE DATE(c.date) = CURRENT_DATE()
        ')->getResult();
    }

    /**
     * Récupère les livraisons d'un livreur
     */
    public function getLivreurDeliveries(Livreur $livreur): array
    {
        return $this->entityManager->createQuery('
            SELECT l FROM App\Entity\Livraison l
            WHERE l.livreur = :livreur
            ORDER BY l.id DESC
        ')->setParameter('livreur', $livreur)->getResult();
    }
}
