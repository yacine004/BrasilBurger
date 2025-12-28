<?php

namespace App\Controller;

use App\Repository\CommandeRepository;
use App\Service\StatisticsService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/statistiques')]
class StatisticsController extends AbstractController
{
    public function __construct(
        private StatisticsService $statisticsService,
        private CommandeRepository $commandeRepository
    ) {}

    #[Route('/', name: 'app_statistics_index')]
    public function index(): Response
    {
        $stats = $this->statisticsService->getStatistiquesJour();

        return $this->render('statistics/index.html.twig', [
            'stats' => $stats,
        ]);
    }

    #[Route('/commandes-en-cours', name: 'app_statistics_en_cours')]
    public function commandesEnCours(): Response
    {
        $commandes = $this->statisticsService->getCommandesEnCours();

        return $this->render('statistics/commandes_en_cours.html.twig', [
            'commandes' => $commandes,
        ]);
    }

    #[Route('/commandes-validees', name: 'app_statistics_validees')]
    public function commandesValidees(): Response
    {
        $commandes = $this->statisticsService->getCommandesValidees();

        return $this->render('statistics/commandes_validees.html.twig', [
            'commandes' => $commandes,
        ]);
    }

    #[Route('/commandes-terminees', name: 'app_statistics_terminees')]
    public function commandesTerminees(): Response
    {
        $commandes = $this->statisticsService->getCommandesTerminees();

        return $this->render('statistics/commandes_terminees.html.twig', [
            'commandes' => $commandes,
        ]);
    }

    #[Route('/commandes-annulees', name: 'app_statistics_annulees')]
    public function commandesAnnulees(): Response
    {
        $commandes = $this->statisticsService->getCommandesAnnulees();

        return $this->render('statistics/commandes_annulees.html.twig', [
            'commandes' => $commandes,
        ]);
    }

    #[Route('/recettes', name: 'app_statistics_recettes')]
    public function recettes(): Response
    {
        $recettes = $this->statisticsService->getRecettesJournalieres();
        $paiements = $this->commandeRepository->findAllToday();

        return $this->render('statistics/recettes.html.twig', [
            'recettes' => $recettes,
            'paiements' => $paiements,
        ]);
    }

    #[Route('/burgers-plus-vendus', name: 'app_statistics_burgers')]
    public function burgersPlusVendus(): Response
    {
        $burgers = $this->statisticsService->getBurgersLesPlusVendus();

        return $this->render('statistics/burgers_plus_vendus.html.twig', [
            'burgers' => $burgers,
        ]);
    }

    #[Route('/filtre', name: 'app_statistics_filter')]
    public function filter(Request $request): Response
    {
        $etat = $request->query->get('etat');
        $clientId = $request->query->get('client_id');
        $startDate = $request->query->get('start_date');
        $endDate = $request->query->get('end_date');

        $commandes = [];

        if ($etat) {
            $commandes = $this->commandeRepository->findByStatus($etat);
        } elseif ($clientId) {
            $commandes = $this->commandeRepository->findByClient($clientId);
        } elseif ($startDate && $endDate) {
            $start = new \DateTime($startDate);
            $end = new \DateTime($endDate);
            $commandes = $this->commandeRepository->findByDateRange($start, $end);
        }

        return $this->render('statistics/filter.html.twig', [
            'commandes' => $commandes,
        ]);
    }
}
