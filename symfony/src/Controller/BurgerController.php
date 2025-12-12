<?php

namespace App\Controller;

use App\Service\ApiService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/burgers')]
class BurgerController extends AbstractController
{
    public function __construct(private ApiService $apiService)
    {
    }

    #[Route('', name: 'app_burger_list', methods: ['GET'])]
    public function list(): Response
    {
        $burgers = $this->apiService->getBurgers() ?? [];

        return $this->render('burger/list.html.twig', [
            'burgers' => $burgers,
            'title' => 'Gestion des Burgers',
        ]);
    }

    #[Route('/{id}', name: 'app_burger_show', methods: ['GET'])]
    public function show(int $id): Response
    {
        $burger = $this->apiService->getBurgerById($id) ?? [];

        if (empty($burger)) {
            throw $this->createNotFoundException('Burger non trouvé');
        }

        return $this->render('burger/show.html.twig', [
            'burger' => $burger,
            'title' => $burger['nom'] ?? 'Détails du Burger',
        ]);
    }

    #[Route('/create', name: 'app_burger_create', methods: ['GET', 'POST'])]
    public function create(Request $request): Response
    {
        if ($request->isMethod('POST')) {
            $data = [
                'nom' => $request->request->get('nom'),
                'description' => $request->request->get('description'),
                'prix' => $request->request->get('prix'),
                'image' => $request->request->get('image'),
                'statut' => $request->request->get('statut', 'ACTIF'),
            ];

            $result = $this->apiService->createBurger($data);

            if (!isset($result['error'])) {
                $this->addFlash('success', 'Burger créé avec succès');
                return $this->redirectToRoute('app_burger_list');
            }

            $this->addFlash('error', 'Erreur lors de la création du burger');
        }

        return $this->render('burger/form.html.twig', [
            'title' => 'Créer un Burger',
            'mode' => 'create',
        ]);
    }

    #[Route('/{id}/edit', name: 'app_burger_edit', methods: ['GET', 'POST'])]
    public function edit(int $id, Request $request): Response
    {
        $burger = $this->apiService->getBurgerById($id) ?? [];

        if (empty($burger)) {
            throw $this->createNotFoundException('Burger non trouvé');
        }

        if ($request->isMethod('POST')) {
            $data = [
                'nom' => $request->request->get('nom'),
                'description' => $request->request->get('description'),
                'prix' => $request->request->get('prix'),
                'image' => $request->request->get('image'),
                'statut' => $request->request->get('statut'),
            ];

            $result = $this->apiService->updateBurger($id, $data);

            if (!isset($result['error'])) {
                $this->addFlash('success', 'Burger modifié avec succès');
                return $this->redirectToRoute('app_burger_list');
            }

            $this->addFlash('error', 'Erreur lors de la modification du burger');
        }

        return $this->render('burger/form.html.twig', [
            'burger' => $burger,
            'title' => 'Modifier: ' . ($burger['nom'] ?? 'Burger'),
            'mode' => 'edit',
        ]);
    }

    #[Route('/{id}/delete', name: 'app_burger_delete', methods: ['POST'])]
    public function delete(int $id): Response
    {
        $result = $this->apiService->deleteBurger($id);

        if (!isset($result['error'])) {
            $this->addFlash('success', 'Burger supprimé avec succès');
        } else {
            $this->addFlash('error', 'Erreur lors de la suppression du burger');
        }

        return $this->redirectToRoute('app_burger_list');
    }
}
