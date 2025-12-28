<?php

namespace App\Command;

use App\Entity\Client;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:seed-clients',
    description: 'Ajoute des clients de test à la base de données'
)]
class SeedClientsCommand extends Command
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        
        $clients = [
            ['Diallo', 'Amadou', '+221771234567', 'amadou@example.com'],
            ['Sow', 'Aïssatou', '+221782345678', 'aissatou@example.com'],
            ['Cissé', 'Ibrahim', '+221753456789', 'ibrahim@example.com'],
            ['Ndiaye', 'Fatima', '+221761234567', 'fatima@example.com'],
        ];

        $count = 0;
        foreach ($clients as [$nom, $prenom, $telephone, $email]) {
            $client = new Client();
            $client->setNom($nom);
            $client->setPrenom($prenom);
            $client->setTelephone($telephone);
            $client->setEmail($email);
            
            $this->entityManager->persist($client);
            $count++;
            $io->note("Client ajouté: {$prenom} {$nom}");
        }

        $this->entityManager->flush();
        
        $io->success("✓ {$count} clients ont été ajoutés");
        return Command::SUCCESS;
    }
}
