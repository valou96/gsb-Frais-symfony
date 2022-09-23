<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ListUsersController extends AbstractController
{
    #[Route('/list/users', name: 'app_list_users')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $repository = $doctrine->getRepository(User::class);
        $users = $repository->findAll();
        return $this->render('list_users/index.html.twig', [
            'users' => $users,
        ]);


    }
}
