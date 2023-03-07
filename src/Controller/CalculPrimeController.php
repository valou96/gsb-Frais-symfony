<?php

namespace App\Controller;

use App\Entity\FicheFrais;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CalculPrimeController extends AbstractController
{
    #[Route('/calcul/prime', name: 'app_calcul_prime')]
    public function index(ManagerRegistry $doctrine): Response
    {

        $repository = $doctrine->getRepository(FicheFrais::class);
        $FicheFrais = $repository->findAll();


        return $this->render('calcul_prime/index.html.twig', [
            'calculPrime' => $FicheFrais,
        ]);
    }
}
