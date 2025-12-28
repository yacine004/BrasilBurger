<?php

namespace App\Controller;

use App\Entity\Livreur;
use App\Repository\LivreurRepository;
use App\Service\LivraisonService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;

#[Route('/livreur')]
class LivreurController extends AbstractController
{
    public function __construct(
        private LivreurRepository $livreurRepository,
        private LivraisonService $livraisonService,
        private EntityManagerInterface $entityManager
    ) {}

    #[Route('/', name: 'app_livreur_index')]
    public function index(): Response
    {
        $livreurs = $this->livreurRepository->findBy([], ['id' => 'ASC']);

        return $this->render('livreur/index.html.twig', [
            'livreurs' => $livreurs,
        ]);
    }

    #[Route('/nouveau', name: 'app_livreur_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        if ($request->isMethod('POST')) {
            $livreur = new Livreur();
            $livreur->setNom($request->request->get('nom'));
            $livreur->setPrenom($request->request->get('prenom'));
            $livreur->setTelephone($request->request->get('telephone'));

            $this->entityManager->persist($livreur);
            $this->entityManager->flush();

            $this->addFlash('success', 'Livreur créé avec succès');
            return $this->redirectToRoute('app_livreur_index');
        }

        return $this->render('livreur/new.html.twig');
    }

    #[Route('/{id}', name: 'app_livreur_show')]
    public function show(Livreur $livreur): Response
    {
        $livraisons = $this->livraisonService->getLivreurDeliveries($livreur);

        return $this->render('livreur/show.html.twig', [
            'livreur' => $livreur,
            'livraisons' => $livraisons,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_livreur_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Livreur $livreur): Response
    {
        if ($request->isMethod('POST')) {
            $livreur->setNom($request->request->get('nom'));
            $livreur->setPrenom($request->request->get('prenom'));
            $livreur->setTelephone($request->request->get('telephone'));

            $this->entityManager->flush();

            $this->addFlash('success', 'Livreur mis à jour');
            return $this->redirectToRoute('app_livreur_show', ['id' => $livreur->getId()]);
        }

        return $this->render('livreur/edit.html.twig', [
            'livreur' => $livreur,
        ]);
    }
}
