<?php

namespace App\Command;

use App\Entity\Livreur;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:format-phone-numbers',
    description: 'Formate tous les numéros de téléphone des livreurs au format sénégalais'
)]
class FormatPhoneNumbersCommand extends Command
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        
        $livreurs = $this->entityManager->getRepository(Livreur::class)->findAll();
        $updated = 0;

        foreach ($livreurs as $livreur) {
            $oldPhone = $livreur->getTelephone();
            // Le setter va formater automatiquement
            $livreur->setTelephone($oldPhone);
            $newPhone = $livreur->getTelephone();
            
            if ($oldPhone !== $newPhone) {
                $io->note("Livreur #{$livreur->getId()}: {$oldPhone} → {$newPhone}");
                $updated++;
            }
        }

        $this->entityManager->flush();
        
        $io->success("✓ {$updated} numéros de téléphone ont été formatés");
        return Command::SUCCESS;
    }
}
