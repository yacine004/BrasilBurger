<?php

namespace App\Repository;

use App\Entity\Client;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Client>
 */
class ClientRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Client::class);
    }

    /**
     * Trouver un client par téléphone
     */
    public function findByTelephone(string $telephone): ?Client
    {
        return $this->findOneBy(['telephone' => $telephone]);
    }

    /**
     * Trouver un client par email
     */
    public function findByEmail(string $email): ?Client
    {
        return $this->findOneBy(['email' => $email]);
    }

    /**
     * Trouver les clients actifs (non archivés)
     */
    public function findClientsActifs(): array
    {
        return $this->findBy(['archive' => false], ['nom' => 'ASC', 'prenom' => 'ASC']);
    }
}
