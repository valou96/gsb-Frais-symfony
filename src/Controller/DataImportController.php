<?php

namespace App\Controller;

use App\Entity\FicheFrais;
use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class DataImportController extends AbstractController
{
    #[Route('/data/import', name: 'app_data_import')]
    public function index(ManagerRegistry $doctrine, UserPasswordHasherInterface $passwordHasher): Response
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


    #[Route('/data/import/fiche-frais', name: 'app_data_import')]
    public function fiche(ManagerRegistry $doctrine, UserPasswordHasherInterface $passwordHasher): Response
    {

        $file = "./fichefrais.json";
        $json = file_get_contents($file);
        $fiches = json_decode($json);
        foreach ($fiches as $fiche) {
            $newFiche = new FicheFrais();
            $newFiche->setMois($fiche->moi);
            $newFiche->setMontantValide($fiche->montantValide);
            $newFiche->setNbJustificatif($fiche->nbjustificatifs);
            $newFiche->setDateModif( new \DateTime($fiche->dateModif));
            $user = $doctrine->getRepository(User::class)->findOneBy(['oldId'=>$fiche->idVisiteur]);
            $newFiche->setUser($user);
            var_dump($user->getID());
        }






        return $this->render('data_import/index.html.twig', [
            'controller_name' => 'DataImportController',
        ]);


    }





}
