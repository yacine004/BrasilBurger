<?php

namespace App\Repository;

use App\Entity\Menu;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Menu>
 */
class MenuRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Menu::class);
    }

    /**
     * Trouver les menus actifs (non archivés)
     */
    public function findMenusActifs(): array
    {
        return $this->findBy(['archive' => false], ['nom' => 'ASC']);
    }

    /**
     * Trouver un menu par nom
     */
    public function findByNom(string $nom): ?Menu
    {
        return $this->findOneBy(['nom' => $nom, 'archive' => false]);
    }
}
