<?php

namespace App\Command;

use App\Entity\Commande;
use App\Service\CommandeService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:calculate-commande-totals',
    description: 'Recalcule les montants totaux de toutes les commandes'
)]
class CalculateCommandeTotalsCommand extends Command
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private CommandeService $commandeService
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        
        $commandes = $this->entityManager->getRepository(Commande::class)->findAll();
        $updated = 0;

        foreach ($commandes as $commande) {
            $oldMontant = $commande->getMontant();
            $this->commandeService->calculateAndSetTotal($commande);
            $newMontant = $commande->getMontant();
            
            if ($oldMontant !== $newMontant) {
                $io->note("Commande #{$commande->getId()}: {$oldMontant} → {$newMontant} FCFA");
                $updated++;
            }
        }

        $this->entityManager->flush();
        
        $io->success("✓ {$updated} commandes ont été mises à jour");
        return Command::SUCCESS;
    }
}
