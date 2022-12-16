<?php

namespace App\Controller;

use App\Entity\Etat;
use App\Entity\FicheFrais;
use App\Entity\FraisForfait;
use App\Entity\LigneFraisForfait;
use App\Entity\LigneHorsForfait;
use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class DataImportController extends AbstractController
{
    #[Route('/data/import/users', name: 'app_data_import_users')]
    public function importUser(ManagerRegistry $doctrine, UserPasswordHasherInterface $passwordHasher): Response
    {

        $file = "./visiteur.json";
        $json = file_get_contents($file);
        $users = json_decode($json);
        foreach ($users as $user) {
            $newUser = new User();
            $newUser->setOldId($user->id);
            $newUser->setLogin($user->login);
            $newUser->setNom($user->nom);
            $newUser->setPrenom($user->prenom);
            $newUser->setAdresse($user->adresse);
            $newUser->setCp($user->cp);
            $newUser->setVille($user->ville);
            $newUser->setDateEmbauche(new \DateTime($user->dateEmbauche));
            $plaintextpassword = $user->mdp; //on stocke le mot de passe en clair dans une variable
            $hashedpassword = $passwordHasher->hashPassword($newUser, $plaintextpassword); //on hache le mot de passe
            $newUser->setPassword($hashedpassword);

            $doctrine->getManager()->persist($newUser); //je fais persister l'objet $newUser en base de données
            $doctrine->getManager()->flush(); //flush est à appeler après avoir fait un persist
        }


        return $this->render('data_import/index.html.twig', [
            'controller_name' => 'DataImportController',
        ]);
    }


    #[Route('/data/import/fiche-frais', name: 'app_data_import_fiches_frais')]
    public function ficheFrais(ManagerRegistry $doctrine): Response
    {

        $file = "./fichefrais.json";
        $json = file_get_contents($file);
        $fiches = json_decode($json);
        foreach ($fiches as $fiche) {
            $newFiche = new FicheFrais();
            $user = $doctrine->getRepository(User::class)->findOneBy(['oldId' => $fiche->idVisiteur]);
            $newFiche->setUser($user);
            $newFiche->setMois($fiche->mois);
            $newFiche->setMontantValide($fiche->montantValide);
            $newFiche->setNbJustificatif($fiche->nbJustificatifs);
            $newFiche->setDateModif(new \DateTime($fiche->dateModif));
            if ($fiche->idEtat == "VA") {
                $etat = $doctrine->getRepository(Etat::class)->find(4);
            } else if ($fiche->idEtat == "RB") {
                $etat = $doctrine->getRepository(Etat::class)->find(3);
            } else if ($fiche->idEtat == "CL") {
                $etat = $doctrine->getRepository(Etat::class)->find(1);
            } else if ($fiche->idEtat == "CR") {
                $etat = $doctrine->getRepository(Etat::class)->find(2);
            }
            $newFiche->setEtat($etat);

            $doctrine->getManager()->persist($newFiche); //je fais persister l'objet $newUser en base de données
            $doctrine->getManager()->flush(); //flush est à appeler après avoir fait un persist
        }


        return $this->render('data_import/index.html.twig', [
            'controller_name' => 'DataImportController',
        ]);
    }


    #[Route('/data/import/ligne_frais_forfait', name: 'app_data_import_ligne_frais_forfait')]
    public function LigneFraisForfait(ManagerRegistry $doctrine): Response
    {

        $file = "./lignefraisforfait.json";
        $json = file_get_contents($file);
        $lignesFraisForfait = json_decode($json);

        foreach ($lignesFraisForfait as $ligneFraisForfait) {
            $newLigne = new LigneFraisForfait();
            $user = $doctrine->getRepository(User::class)->findOneBy(['oldId' => $ligneFraisForfait->idVisiteur]);
            $frais = $doctrine->getRepository(FicheFrais::class)->findOneBy(['user' => $user, 'mois' => $ligneFraisForfait->mois]);
            $newLigne->setFicheFrais($frais);

            if ($ligneFraisForfait->idFraisForfait == "ETP") {
                $idFraisForfait= $doctrine->getRepository(FraisForfait::class)->find(1);
            } else if ($ligneFraisForfait->idFraisForfait == "KM") {
                $idFraisForfait = $doctrine->getRepository(FraisForfait::class)->find(2);
            } else if ($ligneFraisForfait->idFraisForfait == "NUI") {
                $idFraisForfait = $doctrine->getRepository(FraisForfait::class)->find(3);
            } else if ($ligneFraisForfait->idFraisForfait == "REP") {
                $idFraisForfait = $doctrine->getRepository(FraisForfait::class)->find(4);
            }
            $newLigne->setFraisForfait($idFraisForfait);
            $newLigne->setQuantite($ligneFraisForfait->quantite);


            $doctrine->getManager()->persist($newLigne); //je fais persister l'objet $newUser en base de données
            $doctrine->getManager()->flush(); //flush est à appeler après avoir fait un persist
        }


        return $this->render('data_import/index.html.twig', [
            'controller_name' => 'DataImportController',
        ]);
    }

    #[Route('/data/import/ligne_hors_forfait', name: 'app_data_import_ligne_hors_forfait')]
    public function LigneHorsForfait(ManagerRegistry $doctrine): Response
    {

        $file = "./lignefraishorsforfait.json";
        $json = file_get_contents($file);
        $lignesFraisHorsForfait = json_decode($json);
        foreach ($lignesFraisHorsForfait as $ligneFraisHorsForfait) {
            $newLigneHorsForfait = new LigneHorsForfait();
            $user = $doctrine->getRepository(User::class)->findOneBy(['oldId' => $ligneFraisHorsForfait->idVisiteur]);
            $fichefrais = $doctrine->getRepository(FicheFrais::class)->findOneBy(['user' => $user, 'mois' => $ligneFraisHorsForfait->mois]);
            $newLigneHorsForfait->setLibelle($ligneFraisHorsForfait->libelle);
            $newLigneHorsForfait->setDate(new \DateTime($ligneFraisHorsForfait->date));
            $newLigneHorsForfait->setMontant($ligneFraisHorsForfait->montant);
            $newLigneHorsForfait->setFicheFrais($fichefrais);

           $doctrine->getManager()->persist($newLigneHorsForfait); //je fais persister l'objet $newUser en base de données
            $doctrine->getManager()->flush(); //flush est à appeler après avoir fait un persist
        }


        return $this->render('data_import/index.html.twig', [
            'controller_name' => 'DataImportController',
        ]);
    }




}

