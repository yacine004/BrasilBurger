<?php

namespace App\Controller;

use App\Entity\Complement;
use App\Repository\ComplementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/complements', name: 'api_complements_')]
class ComplementController extends AbstractController
{
    public function __construct(
        private ComplementRepository $complementRepository,
        private EntityManagerInterface $em
    ) {}

    /**
     * Lister les compléments
     */
    #[Route('', name: 'list', methods: ['GET'])]
    public function list(): JsonResponse
    {
        $complements = $this->complementRepository->findComplementsActifs();

        return $this->json([
            'success' => true,
            'count' => count($complements),
            'data' => array_map($this->serializeComplement(...), $complements)
        ]);
    }

    /**
     * Récupérer un complément
     */
    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(int $id): JsonResponse
    {
        $complement = $this->complementRepository->find($id);

        if (!$complement || $complement->isArchive()) {
            return $this->json(['error' => 'Complément non trouvé'], 404);
        }

        return $this->json([
            'success' => true,
            'data' => $this->serializeComplement($complement)
        ]);
    }

    /**
     * Serialize un complément
     */
    private function serializeComplement(Complement $complement): array
    {
        return [
            'id' => $complement->getId(),
            'nom' => $complement->getNom(),
            'prix' => (float) $complement->getPrix(),
            'image' => $complement->getImage(),
            'description' => $complement->getDescription(),
            'archive' => $complement->isArchive()
        ];
    }
}
