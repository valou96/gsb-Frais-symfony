<?php

namespace App\Controller;

use App\Entity\Seminaire;
use App\Form\SeminaireType;
use App\Repository\SeminaireRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/seminaire')]
class SeminaireController extends AbstractController
{
    #[Route('/', name: 'app_seminaire_index', methods: ['GET'])]
    public function index(SeminaireRepository $seminaireRepository): Response
    {
        return $this->render('seminaire/index.html.twig', [
            'seminaires' => $seminaireRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_seminaire_new', methods: ['GET', 'POST'])]
    public function new(Request $request, SeminaireRepository $seminaireRepository): Response
    {
        $seminaire = new Seminaire();
        $form = $this->createForm(SeminaireType::class, $seminaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $seminaireRepository->save($seminaire, true);

            return $this->redirectToRoute('app_seminaire_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('seminaire/new.html.twig', [
            'seminaire' => $seminaire,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_seminaire_show', methods: ['GET'])]
    public function show(Seminaire $seminaire): Response
    {
        return $this->render('seminaire/show.html.twig', [
            'seminaire' => $seminaire,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_seminaire_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Seminaire $seminaire, SeminaireRepository $seminaireRepository): Response
    {
        $form = $this->createForm(SeminaireType::class, $seminaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $seminaireRepository->save($seminaire, true);

            return $this->redirectToRoute('app_seminaire_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('seminaire/edit.html.twig', [
            'seminaire' => $seminaire,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_seminaire_delete', methods: ['POST'])]
    public function delete(Request $request, Seminaire $seminaire, SeminaireRepository $seminaireRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$seminaire->getId(), $request->request->get('_token'))) {
            $seminaireRepository->remove($seminaire, true);
        }

        return $this->redirectToRoute('app_seminaire_index', [], Response::HTTP_SEE_OTHER);
    }
}
