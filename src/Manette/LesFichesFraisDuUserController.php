<?php

namespace App\Controller;


use App\Entity\FicheFrais;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LesFichesFraisDuUserController extends AbstractController
{
    #[Route('/les/fiches/frais/du/user', name: 'app_les_fiches_frais_du_user')]
    public function index(ManagerRegistry $doctrine): Response
    {

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();

        $registry = $doctrine->getRepository(FicheFrais::class);
        $fichesFraisDuUser = $registry->findBy(['user' => $user]); // 'user' fait appel à la talbe user, $user stocker getUser

        return $this-> render('les_fiches_frais_du_user/index.html.twig', [
            'fichesFraisDuUser' => $fichesFraisDuUser
        ]);
    }
}
