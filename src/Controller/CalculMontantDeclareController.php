<?php

namespace App\Controller;

use App\Entity\FicheFrais;
use App\Entity\FraisForfait;
use App\Entity\LigneFraisForfait;
use App\Entity\LigneHorsForfait;
use App\Repository\LigneHorsForfaitRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CalculMontantDeclareController extends AbstractController
{
    #[Route('/calcul/montant/declare', name: 'app_calcul_montant_declare')]
    public function index(ManagerRegistry $doctrine): Response
    {

        $repository = $doctrine->getRepository(FraisForfait::class);
        $montant = $repository->findBy(['montant' => $montant]);

        $repository = $doctrine->getRepository(LigneFraisForfait::class);
        $quantite = $repository->findBy(['quantite'=> $quantite]);

        $repository = $doctrine->getRepository(FicheFrais::class);
        $FicheFrais = $repository->findAll();

        $repository = $doctrine->getRepository(LigneHorsForfait::class);
        $horsForfait = $repository->findAll();

        $total = $montant * $quantite + $horsForfait;

        return $this->render('calcul_montant_declare/index.html.twig', [
            'fiche' => $total,
        ]);
    }
}

