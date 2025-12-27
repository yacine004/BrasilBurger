<?php

namespace App\Controller;

use App\Entity\Client;
use App\Repository\ClientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/clients', name: 'api_clients_')]
class ClientController extends AbstractController
{
    public function __construct(
        private ClientRepository $clientRepository,
        private EntityManagerInterface $em
    ) {}

    /**
     * Lister les clients
     */
    #[Route('', name: 'list', methods: ['GET'])]
    public function list(): JsonResponse
    {
        $clients = $this->clientRepository->findClientsActifs();

        return $this->json([
            'success' => true,
            'count' => count($clients),
            'data' => array_map($this->serializeClient(...), $clients)
        ]);
    }

    /**
     * Récupérer un client
     */
    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(int $id): JsonResponse
    {
        $client = $this->clientRepository->find($id);

        if (!$client || $client->isArchive()) {
            return $this->json(['error' => 'Client non trouvé'], 404);
        }

        return $this->json([
            'success' => true,
            'data' => $this->serializeClient($client)
        ]);
    }

    /**
     * Chercher un client par téléphone
     */
    #[Route('/search/telephone/{telephone}', name: 'search_telephone', methods: ['GET'])]
    public function searchByTelephone(string $telephone): JsonResponse
    {
        $client = $this->clientRepository->findByTelephone($telephone);

        if (!$client || $client->isArchive()) {
            return $this->json(['error' => 'Client non trouvé'], 404);
        }

        return $this->json([
            'success' => true,
            'data' => $this->serializeClient($client)
        ]);
    }

    /**
     * Serialize un client
     */
    private function serializeClient(Client $client): array
    {
        return [
            'id' => $client->getId(),
            'nom' => $client->getNom(),
            'prenom' => $client->getPrenom(),
            'telephone' => $client->getTelephone(),
            'email' => $client->getEmail(),
            'adresse' => $client->getAdresse(),
            'quartier' => $client->getQuartier(),
            'dateCreation' => $client->getDateCreation()->format('Y-m-d H:i:s'),
            'archive' => $client->isArchive()
        ];
    }
}
