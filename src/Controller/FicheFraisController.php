<?php

namespace App\Controller;

use App\Entity\FicheFrais;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FicheFraisController extends AbstractController
{
    #[Route('/fiche/frais', name: 'app_fiche_frais')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();



        $repository = $doctrine->getRepository(FicheFrais::class);
        $FicheFrais = $repository->findBy(['user'=>$user]);


        return $this->render('fiche_frais/index.html.twig', [
            'FicheFrais' => $FicheFrais,
        ]);
    }
}
