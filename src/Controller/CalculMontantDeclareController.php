<?php

namespace App\Controller;

use App\Entity\FicheFrais;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CalculMontantDeclareController extends AbstractController
{
    #[Route('/calcul/montant/declare', name: 'app_calcul_montant_declare')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $repository = $doctrine->getRepository(FicheFrais::class);


        return $this->render('calcul_montant_declare/index.html.twig', [
            'controller_name' => 'CalculMontantDeclareController',
        ]);
    }
}
