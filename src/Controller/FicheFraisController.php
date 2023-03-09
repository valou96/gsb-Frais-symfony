<?php

namespace App\Controller;

use App\Entity\FicheFrais;
use App\Entity\User;
use App\Repository\FicheFraisRepository;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

#[Route('/ficheFrais')]
class FicheFraisController extends AbstractController
{
    #[Route('/', name: 'app_fiche_frais')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();
        $ficheFrais = new FicheFrais();

        $repository = $doctrine->getRepository(FicheFrais::class);
        $lesfichesFrais = $repository->findBy(['user'=>$user]);
        $dateModif = $ficheFrais->getDateModif();
        $state = $ficheFrais->getEtat();
        if($state == 1){
            $state = 'Saisie cloturée';
        }elseif($state == 2){
            $state = 'Fiche créée, saisie en cours';
        }elseif($state == 3){
            $state = 'Remboursée';
        }elseif($state == 4){
            $state = 'Validée et mise en paiement';
        }

        return $this->render('fiche_frais/index.html.twig', [
            'FicheFrais' => $lesfichesFrais,
            'state' => $state,
            'date'=>$dateModif,
        ]);
    }

    #[Route('/new', name: 'app_new_fiche_frais')]
    public function new(Request $request, ManagerRegistry $doctrine, FicheFraisRepository $ficheFraisRepository, UserRepository $userRepository): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();
        $ficheFrais = new FicheFrais();
        $form = $this->createForm(FicheFrais::class, $ficheFrais);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ficheFraisRepository->save($ficheFrais, true);
            $userRepository->save($user, true);
            $ficheFrais->setNbJustificatif(1);
            $ficheFrais->setEtat(1);
            $ficheFrais->setDateModif(new \DateTime('now'));
            $ficheFrais->setMontantValide($form->get('montantValide')->getData());
            $ficheFrais->setMois($form->get('mois')->getData());
            return $this->redirectToRoute('app_fiche_frais', ['id' => $user->getUserIdentifier()], Response::HTTP_SEE_OTHER);
        }

        return $this->render(':fiche_frais:add.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }
}
