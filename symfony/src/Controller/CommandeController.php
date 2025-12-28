<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Repository\CommandeRepository;
use App\Service\CommandeService;
use App\Form\CommandeType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;

#[Route('/commande')]
class CommandeController extends AbstractController
{
    public function __construct(
        private CommandeRepository $commandeRepository,
        private CommandeService $commandeService,
        private EntityManagerInterface $entityManager
    ) {}

    #[Route('/', name: 'app_commande_index')]
    public function index(): Response
    {
        $commandes = $this->commandeRepository->findBy([], ['date' => 'DESC']);

        return $this->render('commande/index.html.twig', [
            'commandes' => $commandes,
        ]);
    }

    #[Route('/nouveau', name: 'app_commande_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $commande = new Commande();
        $form = $this->createForm(CommandeType::class, $commande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $commande->setDate($commande->getDate() ?? new \DateTime());
            $this->entityManager->persist($commande);
            $this->entityManager->flush();

            $this->addFlash('success', 'Commande créée avec succès');
            return $this->redirectToRoute('app_commande_show', ['id' => $commande->getId()]);
        }

        return $this->render('commande/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_commande_show')]
    public function show(Commande $commande): Response
    {
        $details = $this->commandeService->getCommandeDetails($commande);

        return $this->render('commande/show.html.twig', [
            'commande' => $commande,
            'details' => $details,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_commande_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Commande $commande): Response
    {
        if ($request->isMethod('POST')) {
            $etat = $request->request->get('etat');
            if ($etat) {
                $this->commandeService->updateCommandeStatus($commande, $etat);
                $this->addFlash('success', 'Commande mise à jour avec succès');
                return $this->redirectToRoute('app_commande_show', ['id' => $commande->getId()]);
            }
        }

        return $this->render('commande/edit.html.twig', [
            'commande' => $commande,
        ]);
    }

    #[Route('/{id}/ready', name: 'app_commande_ready', methods: ['POST'])]
    public function markAsReady(Commande $commande): Response
    {
        $this->commandeService->markAsReady($commande);
        $this->addFlash('success', 'Commande marquée comme prête');

        return $this->redirectToRoute('app_commande_show', ['id' => $commande->getId()]);
    }

    #[Route('/{id}/cancel', name: 'app_commande_cancel', methods: ['POST'])]
    public function cancel(Commande $commande): Response
    {
        $this->commandeService->cancelCommande($commande);
        $this->addFlash('success', 'Commande annulée');

        return $this->redirectToRoute('app_commande_index');
    }

    #[Route('/{id}/delete', name: 'app_commande_delete', methods: ['POST'])]
    public function delete(Commande $commande): Response
    {
        $this->commandeService->deleteCommande($commande);
        $this->addFlash('success', 'Commande supprimée');

        return $this->redirectToRoute('app_commande_index');
    }
}
