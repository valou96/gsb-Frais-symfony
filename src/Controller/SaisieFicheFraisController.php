<?php

namespace App\Controller;

use App\Entity\Etat;
use App\Entity\FicheFrais;
use App\Entity\LigneHorsForfait;
use App\Form\FicheFraisType;
use App\Form\LigneFraisForfaitType;
use App\Repository\FicheFraisRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Constraints\DateTime;

#[Route('/saisie/fiche/frais')]
class SaisieFicheFraisController extends AbstractController
{
    #[Route('/', name: 'app_saisie_fiche_frais_index', methods: ['GET'])]
    public function index(FicheFraisRepository $ficheFraisRepository): Response
    {
        return $this->render('saisie_fiche_frais/index.html.twig', [
            'fiche_frais' => $ficheFraisRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_saisie_fiche_frais_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, FicheFraisRepository $ficheFraisRepository): Response
    {
        $ficheFrai = new FicheFrais();
        $date = new \DateTime('now' );
        $ficheFrai->setNbJustificatif(0);
        $ficheFrai->setDateModif($date);
        $formFF = $this->createForm(FicheFraisType::class, $ficheFrai);
        $formFF->handleRequest($request);

        if ($formFF->isSubmitted() && $formFF->isValid()) {

            $ficheFraisRepository->save($ficheFrai, true);
            $entityManager->persist($ficheFrai);
            $entityManager->flush();

            return $this->redirectToRoute('app_les_fiches_frais_du_user', [], Response::HTTP_SEE_OTHER);
        }

        $formHF = $this->createForm(LigneFraisForfaitType::class, $ficheFrai);
        $formHF->handleRequest($request);

        if ($formHF->isSubmitted() && $formHF->isValid()) {
            $FF = new LigneHorsForfait();
            $totalFF =+ $FF->getMontant();
            $ficheFrai->setMontantValide($totalFF);
            $ficheFraisRepository->save($ficheFrai, true);
            $entityManager->persist($ficheFrai);
            $entityManager->flush();

            return $this->redirectToRoute('app_les_fiches_frais_du_user', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('saisie_fiche_frais/index.html.twig', [
            'fiche_frai' => $ficheFrai,
            'formHF' => $formHF,
            'formFF' => $formFF,
        ]);
    }
}
