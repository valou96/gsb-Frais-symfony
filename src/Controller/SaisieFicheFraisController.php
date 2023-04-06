<?php

namespace App\Controller;

use App\Entity\Etat;
use App\Entity\FicheFrais;
use App\Entity\FraisForfait;
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
        voir si la fiche existe
        interdire l'acces à un utilisateur non authentifié
        recuperer l'utilisateur authentifié
        recuperer le mois en cours
        recuperer la fiche de frais de l'utilisateur connecté pour le mois en cours
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

        $date = new \DateTime('now');
        $mois = \date('Ym');
        $repositoryFF = $entityManager->getRepository(FicheFrais::class);
        $repositoryEtat = $entityManager->getRepository(Etat::class);

        $etat = $repositoryEtat->find(['id'=>2]);
        $fichesFraisEnCours = $repositoryFF->findOneBy(['user' => $user, 'mois' => $mois]);

        if ($fichesFraisEnCours == null) {
            $fichesFraisEnCours = new FicheFrais();

            $repositoryFf1 = $entityManager->getRepository(FraisForfait::class);
            $ff1 = $repositoryFf1->find(['id'=>1]);
            $repositoryFf2 = $entityManager->getRepository(FraisForfait::class);
            $ff2 = $repositoryFf2->find(['id'=>2]);
            $repositoryFf3 = $entityManager->getRepository(FraisForfait::class);
            $ff3 = $repositoryFf3->find(['id'=>3]);
            $repositoryFf4 = $entityManager->getRepository(FraisForfait::class);
            $ff4 = $repositoryFf4->find(['id'=>4]);

            $ligneFFEtape = new LigneFraisForfait();
            $ligneFFEtape->setFraisForfait($ff1);
            $ligneFFEtape->setQuantite(0);
            $ligneFFEtape->setFicheFrais($fichesFraisEnCours);
            $ligneFFKm = new LigneFraisForfait();
            $ligneFFKm->setFraisForfait($ff2);
            $ligneFFKm->setQuantite(0);
            $ligneFFKm->setFicheFrais($fichesFraisEnCours);
            $ligneFFNui = new LigneFraisForfait();
            $ligneFFNui->setFraisForfait($ff3);
            $ligneFFNui->setQuantite(0);
            $ligneFFNui->setFicheFrais($fichesFraisEnCours);
            $ligneFFRep = new LigneFraisForfait();
            $ligneFFRep->setFraisForfait($ff4);
            $ligneFFRep->setQuantite(0);
            $ligneFFRep->setFicheFrais($fichesFraisEnCours);


            $fichesFraisEnCours->setMois($mois);
            $fichesFraisEnCours->setEtat($etat);
            $fichesFraisEnCours->setUser($user);
            $fichesFraisEnCours->setDateModif($date);
            $fichesFraisEnCours->setNbJustificatif(0);
            $fichesFraisEnCours->setMontantValide(0.00);
            $fichesFraisEnCours->addLigneFraisForfait($ligneFFEtape);
            $fichesFraisEnCours->addLigneFraisForfait($ligneFFKm);
            $fichesFraisEnCours->addLigneFraisForfait($ligneFFNui);
            $fichesFraisEnCours->addLigneFraisForfait($ligneFFRep);

            $entityManager->persist($fichesFraisEnCours);
            $entityManager->flush();
        }

            $hf = new LigneHorsForfait();

            $formHF = $this->createForm(LigneHorsForfaitType::class, $hf);
            $formHF->handleRequest($request);
            if ($formHF->isSubmitted() && $formHF->isValid()) {


                $fichesFraisEnCours->addLigneHorsForfait($hf);
               // $HF->setLibelle($formHF->getData());

                $entityManager->persist($fichesFraisEnCours);
                $entityManager->flush();

                return $this->redirectToRoute('app_fiche_frais_index', [], Response::HTTP_SEE_OTHER);
            }



            $formFF = $this->createForm(LigneFraisForfaitType::class);
            $formFF->handleRequest($request);

            if ($formFF->isSubmitted() && $formFF->isValid()) {

                $fichesFraisEnCours->getLigneFraisForfait()[0]->setQuantite($formFF->get('libelleETP')->getData());
                $fichesFraisEnCours->getLigneFraisForfait()[1]->setQuantite($formFF->get('libelleKM')->getData());
                $fichesFraisEnCours->getLigneFraisForfait()[2]->setQuantite($formFF->get('libelleNUI')->getData());
                $fichesFraisEnCours->getLigneFraisForfait()[3]->setQuantite($formFF->get('libelleREP')->getData());


                $entityManager->persist($fichesFraisEnCours);
                $entityManager->flush();

                return $this->redirectToRoute('app_fiche_frais_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('saisie_fiche_frais/index.html.twig', [
                'fiche_frai' => $fichesFraisEnCours,
                'formHF' => $formHF,
                'formFF' => $formFF,
            ]);
        }

}
