<?php

namespace App\Controller;

use App\Entity\Menu;
use App\Repository\MenuRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/menus', name: 'api_menus_')]
class MenuController extends AbstractController
{
    public function __construct(
        private MenuRepository $menuRepository,
        private EntityManagerInterface $em
    ) {}

    /**
     * Lister les menus
     */
    #[Route('', name: 'list', methods: ['GET'])]
    public function list(): JsonResponse
    {
        $menus = $this->menuRepository->findMenusActifs();

        return $this->json([
            'success' => true,
            'count' => count($menus),
            'data' => array_map($this->serializeMenu(...), $menus)
        ]);
    }

    /**
     * Récupérer un menu
     */
    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(int $id): JsonResponse
    {
        $menu = $this->menuRepository->find($id);

        if (!$menu || $menu->isArchive()) {
            return $this->json(['error' => 'Menu non trouvé'], 404);
        }

        return $this->json([
            'success' => true,
            'data' => $this->serializeMenu($menu)
        ]);
    }

    /**
     * Serialize un menu
     */
    private function serializeMenu(Menu $menu): array
    {
        return [
            'id' => $menu->getId(),
            'nom' => $menu->getNom(),
            'prix' => (float) $menu->getPrix(),
            'image' => $menu->getImage(),
            'description' => $menu->getDescription(),
            'archive' => $menu->isArchive()
        ];
    }
}
