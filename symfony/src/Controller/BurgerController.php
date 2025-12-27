<?php

namespace App\Controller;

use App\Entity\Burger;
use App\Repository\BurgerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/burgers', name: 'api_burgers_')]
class BurgerController extends AbstractController
{
    public function __construct(
        private BurgerRepository $burgerRepository,
        private EntityManagerInterface $em
    ) {}

    /**
     * Lister les burgers
     */
    #[Route('', name: 'list', methods: ['GET'])]
    public function list(): JsonResponse
    {
        $burgers = $this->burgerRepository->findBurgersActifs();

        return $this->json([
            'success' => true,
            'count' => count($burgers),
            'data' => array_map($this->serializeBurger(...), $burgers)
        ]);
    }

    /**
     * Récupérer un burger
     */
    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(int $id): JsonResponse
    {
        $burger = $this->burgerRepository->find($id);

        if (!$burger || $burger->isArchive()) {
            return $this->json(['error' => 'Burger non trouvé'], 404);
        }

        return $this->json([
            'success' => true,
            'data' => $this->serializeBurger($burger)
        ]);
    }

    /**
     * Serialize un burger
     */
    private function serializeBurger(Burger $burger): array
    {
        return [
            'id' => $burger->getId(),
            'nom' => $burger->getNom(),
            'prix' => (float) $burger->getPrix(),
            'image' => $burger->getImage(),
            'description' => $burger->getDescription(),
            'archive' => $burger->isArchive()
        ];
    }
}
