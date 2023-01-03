<?php

namespace App\Controller;

use App\Entity\FicheFrais;
use App\Entity\FraisForfait;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DetailFicheFraisController extends AbstractController
{
    #[Route('/detail/fiche/frais', name: 'app_detail_fiche_frais')]
    public function index(ManagerRegistry $doctrine): Response
    {

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();

        $registry = $doctrine->getRepository(FraisForfait::class);
        $fichesFraisDuUser = $registry->findBy(['fraisforfait_id' => $user]);

        return $this-> render('les_fiches_frais_du_user/index.html.twig', [
            'fichesFraisDuUser' => $fichesFraisDuUser
        ]);

    }
}
