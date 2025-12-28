<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Service\StatisticsService;

class DashboardController extends AbstractController
{
    public function __construct(
        private StatisticsService $statisticsService
    ) {}

    #[Route('/', name: 'app_index')]
    public function index(): Response
    {
        return $this->render('base.html.twig');
    }

    #[Route('/dashboard', name: 'app_dashboard')]
    public function dashboard(): Response
    {
        $stats = $this->statisticsService->getStatistiquesJour();

        return $this->render('dashboard/index.html.twig', [
            'stats' => $stats,
        ]);
    }
}
