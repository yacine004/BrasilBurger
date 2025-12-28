<?php

namespace App\Service;

use App\Entity\Paiement;
use App\Entity\Commande;
use Doctrine\ORM\EntityManagerInterface;

class PaiementService
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {}

    /**
     * Enregistre un paiement pour une commande
     */
    public function recordPayment(Commande $commande, float $montant, string $mode = 'Wave'): Paiement
    {
        // Vérifier si une commande ne peut être payée qu'une seule fois
        if ($commande->getPaiement() !== null) {
            throw new \Exception('Cette commande a déjà été payée');
        }

        $paiement = new Paiement();
        $paiement->setCommande($commande);
        $paiement->setMontant($montant);
        $paiement->setMode($mode);
        $paiement->setDate(new \DateTime());

        $this->entityManager->persist($paiement);

        // Marquer la commande comme validée après paiement
        $commande->setEtat('Validée');

        $this->entityManager->flush();

        return $paiement;
    }

    /**
     * Vérifie si une commande est payée
     */
    public function isCommandePayed(Commande $commande): bool
    {
        return $commande->getPaiement() !== null;
    }

    /**
     * Récupère les paiements du jour
     */
    public function getTodayPayments(): array
    {
        return $this->entityManager->createQuery('
            SELECT p FROM App\Entity\Paiement p
            WHERE DATE(p.date) = CURRENT_DATE()
        ')->getResult();
    }

    /**
     * Récupère le montant total des paiements du jour
     */
    public function getTodayPaymentTotal(): float
    {
        $result = $this->entityManager->createQuery('
            SELECT SUM(p.montant) as total FROM App\Entity\Paiement p
            WHERE DATE(p.date) = CURRENT_DATE()
        ')->getOneOrNullResult();

        return $result['total'] ?? 0;
    }
}
