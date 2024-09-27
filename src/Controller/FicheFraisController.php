<?php

namespace App\Controller;

use App\Entity\FicheFrais;
use App\Entity\FraisForfait;
use App\Entity\LigneFraisForfait;
use App\Form\FicheFraisType;
use App\Repository\FicheFraisRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/ficheFrais')]
class FicheFraisController extends AbstractController
{
    #[Route('/', name: 'app_fiche_frais_index', methods: ['GET', 'POST'])]
    public function index(EntityManagerInterface $doctrine, Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $uneFicheMois = null;
        $user = $this->getUser();

        $moisUser = [];
        $repository = $doctrine->getRepository(FicheFrais::class);

        $lesfichesFrais = $repository->findBy(['user'=>$user]);

        foreach ($lesfichesFrais as $uneFicheFrais) {
            $moisUser[] = $uneFicheFrais->getMois();
        }

        $form = $this->createForm(FicheFraisType::class, null, ['mois'=>$moisUser]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $mois = $form->getData();
            $uneFicheMois = $repository->findOneBy(['user'=>$user, 'mois'=>$mois]);
        }
        return $this->render('fiche_frais/index.html.twig', [

            'mois' => $moisUser,
            'form' => $form,
            'uneFicheMois' => $uneFicheMois,

        ]);
    }

    #[Route('/new', name: 'app_fiche_frais_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $ficheFrais = new FicheFrais();
        $form = $this->createForm(FicheFraisType::class, $ficheFrais);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($ficheFrais);
            $entityManager->flush();

            return $this->redirectToRoute('app_fiche_frais_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('fiche_frais/new.html.twig', [
            'fiche_frais' => $ficheFrais,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_fiche_frais_show', methods: ['GET'])]
    public function show(FicheFrais $ficheFrai): Response
    {
        return $this->render('fiche_frais/show.html.twig', [
            'fiche_frai' => $ficheFrai,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_fiche_frais_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, FicheFrais $ficheFrai, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(FicheFraisType::class, $ficheFrai);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_fiche_frais_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('fiche_frais/edit.html.twig', [
            'fiche_frai' => $ficheFrai,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_fiche_frais_delete', methods: ['POST'])]
    public function delete(Request $request, FicheFrais $ficheFrai, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$ficheFrai->getId(), $request->request->get('_token'))) {
            $entityManager->remove($ficheFrai);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_fiche_frais_index', [], Response::HTTP_SEE_OTHER);
    }
}
