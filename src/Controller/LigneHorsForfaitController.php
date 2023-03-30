<?php

namespace App\Controller;

use App\Entity\LigneHorsForfait;
use App\Form\LigneFraisForfaitType;
use App\Repository\LigneHorsForfaitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/ligne/hors/forfait')]
class LigneHorsForfaitController extends AbstractController
{
    #[Route('/', name: 'app_ligne_hors_forfait_index', methods: ['GET'])]
    public function index(LigneHorsForfaitRepository $ligneHorsForfaitRepository): Response
    {
        return $this->render('ligne_hors_forfait/index.html.twig', [
            'ligne_hors_forfaits' => $ligneHorsForfaitRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_ligne_hors_forfait_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $ligneHorsForfait = new LigneHorsForfait();
        $form = $this->createForm(LigneFraisForfaitType::class, $ligneHorsForfait);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($ligneHorsForfait);
            $entityManager->flush();

            return $this->redirectToRoute('app_ligne_hors_forfait_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('ligne_hors_forfait/new.html.twig', [
            'ligne_hors_forfait' => $ligneHorsForfait,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_ligne_hors_forfait_show', methods: ['GET'])]
    public function show(LigneHorsForfait $ligneHorsForfait): Response
    {
        return $this->render('ligne_hors_forfait/show.html.twig', [
            'ligne_hors_forfait' => $ligneHorsForfait,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_ligne_hors_forfait_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, LigneHorsForfait $ligneHorsForfait, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(LigneFraisForfaitType::class, $ligneHorsForfait);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_ligne_hors_forfait_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('ligne_hors_forfait/edit.html.twig', [
            'ligne_hors_forfait' => $ligneHorsForfait,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_ligne_hors_forfait_delete', methods: ['POST'])]
    public function delete(Request $request, LigneHorsForfait $ligneHorsForfait, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$ligneHorsForfait->getId(), $request->request->get('_token'))) {
            $entityManager->remove($ligneHorsForfait);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_ligne_hors_forfait_index', [], Response::HTTP_SEE_OTHER);
    }
}
