<?php

namespace App\Service;

class ApiService
{
    private string $apiBaseUrl = 'http://localhost:8080/api';
    private string $apiUser = 'user';
    private string $apiPassword = '0ddcd900-1954-441e-8f99-9dd1487745c8';

    private function callApi(string $endpoint, string $method = 'GET', array $data = []): array
    {
        $url = $this->apiBaseUrl . $endpoint;
        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_USERPWD, $this->apiUser . ':' . $this->apiPassword);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

        if ($method !== 'GET' && !empty($data)) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        }

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode !== 200) {
            return ['error' => true, 'message' => "API Error: HTTP $httpCode"];
        }

        return json_decode($response, true) ?? [];
    }

    public function getCommandes(): array
    {
        return $this->callApi('/commandes');
    }

    public function getBurgers(): array
    {
        return $this->callApi('/burgers');
    }

    public function getComplements(): array
    {
        return $this->callApi('/complements');
    }

    public function getMenus(): array
    {
        return $this->callApi('/menus');
    }

    public function getZones(): array
    {
        return $this->callApi('/zones');
    }

    public function getCommandeById(int $id): array
    {
        return $this->callApi('/commandes/' . $id);
    }

    public function getBurgerById(int $id): array
    {
        return $this->callApi('/burgers/' . $id);
    }

    public function createCommande(array $data): array
    {
        return $this->callApi('/commandes', 'POST', $data);
    }

    public function updateCommande(int $id, array $data): array
    {
        return $this->callApi('/commandes/' . $id, 'PUT', $data);
    }

    public function deleteCommande(int $id): array
    {
        return $this->callApi('/commandes/' . $id, 'DELETE');
    }

    public function createBurger(array $data): array
    {
        return $this->callApi('/burgers', 'POST', $data);
    }

    public function updateBurger(int $id, array $data): array
    {
        return $this->callApi('/burgers/' . $id, 'PUT', $data);
    }

    public function deleteBurger(int $id): array
    {
        return $this->callApi('/burgers/' . $id, 'DELETE');
    }
}
