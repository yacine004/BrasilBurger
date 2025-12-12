<?php

namespace App\Service;

class CommandeService
{
    public function __construct(private ApiService $apiService)
    {
    }

    public function getAllCommandes(): array
    {
        return $this->apiService->getCommandes() ?? [];
    }

    public function getCommandeById(int $id): array
    {
        return $this->apiService->getCommandeById($id) ?? [];
    }

    public function filterCommandes(array $commandes, array $filters = []): array
    {
        $filtered = $commandes;

        if (!empty($filters['clientNom'])) {
            $filtered = array_filter($filtered, function ($c) use ($filters) {
                $nom = ($c['clientNom'] ?? '') . ' ' . ($c['clientPrenom'] ?? '');
                return stripos($nom, $filters['clientNom']) !== false;
            });
        }

        if (!empty($filters['statut'])) {
            $filtered = array_filter($filtered, function ($c) use ($filters) {
                return ($c['statut'] ?? '') === $filters['statut'];
            });
        }

        if (!empty($filters['typeCommande'])) {
            $filtered = array_filter($filtered, function ($c) use ($filters) {
                return ($c['typeCommande'] ?? '') === $filters['typeCommande'];
            });
        }

        return array_values($filtered);
    }

    public function createCommande(array $data): array
    {
        return $this->apiService->createCommande($data) ?? [];
    }

    public function updateCommande(int $id, array $data): array
    {
        return $this->apiService->updateCommande($id, $data) ?? [];
    }

    public function deleteCommande(int $id): array
    {
        return $this->apiService->deleteCommande($id) ?? [];
    }

    public function getCommandesEnCours(): array
    {
        $commandes = $this->getAllCommandes();
        return array_filter($commandes, function ($c) {
            return ($c['statut'] ?? '') === 'EN_COURS';
        });
    }

    public function getCommandesValidees(): array
    {
        $commandes = $this->getAllCommandes();
        return array_filter($commandes, function ($c) {
            return ($c['statut'] ?? '') === 'VALIDEE';
        });
    }
}
