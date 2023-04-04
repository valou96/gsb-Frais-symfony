<?php

namespace App\Controller;

use App\Entity\Etat;
use App\Entity\FicheFrais;
use App\Entity\LigneFraisForfait;
use App\Entity\LigneHorsForfait;
use App\Form\FicheFraisType;
use App\Form\LigneFraisForfaitType;
use App\Form\LigneHorsForfaitType;
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
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();
        /*saisie du mois ne cours
        voir si la fiche existe DONE
        interdire l'acces à un utilisateur non authentifié DONE
        recuperer l'utilisateur authentifié DONE
        recuperer le mois en cours DONE
        recupere la fiche de frais de l'utilisateur connecté pour le mois en cours
        si elle n'existe pas, je l'a créé
        form
        formulaire pour les lignes de frais forfaitisé (etape, km, nuitée, repas)
        textbox pour la quantité

        controller
        créer le formimaire
        gerer la validation du formulaire
        recuperer les quantités saisies dans le formulaire
        mettre à jour les lignes de frais forfait de ma fiche frais du mois en cours
        faire persister des données

        Pour les frais hors forfait
        créer un form
        gerer la validation du formulaire
        recuperer la date, le libelle et le montant saisie
        créer une nouvelle ligne de frais hors forfait
        ajouter cette ligne de frais hors forfait à la fiche frais du mois en cours
        faire persister les données
                */
        $ficheFrai = new FicheFrais();
        $date = new \DateTime('now');
        $ficheFrai->setNbJustificatif(0);
        $ficheFrai->setDateModif($date);

        $repository = $entityManager->getRepository(FicheFrais::class);
        $mois = \date('YYYYMM');
        $lesfichesFrais = $repository->findOneBy(['user' => $user, 'mois' => $mois]);
        if ($lesfichesFrais == null) {

            $formFF = $this->createForm(LigneHorsForfaitType::class, $ficheFrai);
            $formFF->handleRequest($request);

            if ($formFF->isSubmitted() && $formFF->isValid()) {
                $HF = new LigneHorsForfait();

                $ficheFraisRepository->save($ficheFrai, true);
                $entityManager->persist($ficheFrai);
                $entityManager->flush();

                return $this->redirectToRoute('app_les_fiches_frais_du_user', [], Response::HTTP_SEE_OTHER);
            }

            $formHF = $this->createForm(LigneFraisForfaitType::class, $ficheFrai);
            $formHF->handleRequest($request);

            if ($formHF->isSubmitted() && $formHF->isValid()) {
                $FF = new LigneFraisForfait();
                $typeFF = $FF->getFraisForfait();
                $totalFF = $typeFF * $FF->getQuantite();
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
}
