<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/gestionnaire')]
class GestionnairesController extends AbstractController
{
    private string $apiBaseUrl = 'http://localhost:8080/api';
    private string $apiUser = 'user';
    private string $apiPassword = '0ddcd900-1954-441e-8f99-9dd1487745c8';

    private function callApi(string $endpoint): array
    {
        try {
            $url = $this->apiBaseUrl . $endpoint;
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
            curl_setopt($ch, CURLOPT_USERPWD, $this->apiUser . ':' . $this->apiPassword);
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            
            $response = curl_exec($ch);
            curl_close($ch);
            
            return json_decode($response, true) ?? [];
        } catch (\Exception $e) {
            return [];
        }
    }

    #[Route('/', name: 'gestionnaire_dashboard')]
    public function dashboard(): Response
    {
        $commandes = $this->callApi('/commandes');

        return $this->render('gestionnaire/dashboard.html.twig', [
            'totalCommandes' => count($commandes),
            'commandes' => $commandes,
        ]);
    }

    #[Route('/commandes', name: 'gestionnaire_commandes')]
    public function commandes(): Response
    {
        $commandes = $this->callApi('/commandes');

        return $this->render('gestionnaire/commandes.html.twig', [
            'commandes' => $commandes,
        ]);
    }

    #[Route('/burgers', name: 'gestionnaire_burgers')]
    public function burgers(): Response
    {
        $burgers = $this->callApi('/burgers');

        return $this->render('gestionnaire/burgers.html.twig', [
            'burgers' => $burgers,
        ]);
    }

    #[Route('/statistiques', name: 'gestionnaire_statistiques')]
    public function statistiques(): Response
    {
        $commandes = $this->callApi('/commandes');

        // Calculer les statistiques
        $stats = [
            'commandesEnCours' => 0,
            'commandesValidees' => 0,
            'recettesJournalieres' => 0,
            'commandesAnnulees' => 0,
            'burgersPlusVendus' => [],
        ];

        return $this->render('gestionnaire/statistiques.html.twig', [
            'stats' => $stats,
        ]);
    }
}
