<?php

namespace App\Command;

use App\Entity\CommandeBurger;
use App\Entity\CommandeMenu;
use App\Entity\CommandeComplement;
use App\Repository\CommandeRepository;
use App\Repository\BurgerRepository;
use App\Repository\MenuRepository;
use App\Repository\ComplementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:seed-commande-products',
    description: 'Ajoute des produits aux commandes existantes'
)]
class SeedCommandeProductsCommand extends Command
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private CommandeRepository $commandeRepository,
        private BurgerRepository $burgerRepository,
        private MenuRepository $menuRepository,
        private ComplementRepository $complementRepository
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        
        $commandes = $this->commandeRepository->findAll();
        $burgers = $this->burgerRepository->findAll();
        $menus = $this->menuRepository->findAll();
        $complements = $this->complementRepository->findAll();

        $added = 0;

        foreach ($commandes as $index => $commande) {
            // Ajouter 1-2 burgers par commande
            if (!empty($burgers)) {
                for ($i = 0; $i < rand(1, 2); $i++) {
                    $burger = $burgers[array_rand($burgers)];
                    $cb = new CommandeBurger();
                    $cb->setCommande($commande);
                    $cb->setBurger($burger);
                    $cb->setQte(rand(1, 3));
                    $this->entityManager->persist($cb);
                    $added++;
                }
            }

            // Ajouter 0-1 menu par commande
            if (!empty($menus) && rand(0, 1)) {
                $menu = $menus[array_rand($menus)];
                $cm = new CommandeMenu();
                $cm->setCommande($commande);
                $cm->setMenu($menu);
                $cm->setQte(1);
                $this->entityManager->persist($cm);
                $added++;
            }

            // Ajouter 0-2 compléments par commande
            if (!empty($complements)) {
                for ($i = 0; $i < rand(0, 2); $i++) {
                    $complement = $complements[array_rand($complements)];
                    $cc = new CommandeComplement();
                    $cc->setCommande($commande);
                    $cc->setComplement($complement);
                    $cc->setQte(rand(1, 2));
                    $this->entityManager->persist($cc);
                    $added++;
                }
            }

            $io->note("Commande #{$commande->getId()}: produits ajoutés");
        }

        $this->entityManager->flush();
        
        $io->success("✓ {$added} produits ont été ajoutés aux commandes");
        return Command::SUCCESS;
    }
}
