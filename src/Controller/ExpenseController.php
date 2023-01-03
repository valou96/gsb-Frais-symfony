<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ExpenseController extends AbstractController
{
    #[Route('/expense', name: 'app_expense')]
    public function listAction(Request $request, EntityManagerInterface $em)
    {
        $userId = $request->attributes->get('id');
        $expenses = $em->getRepository(Expense::class)->findBy(['user' => $userId]);

        return $this->render('expenses/list.html.twig', [
            'expenses' => $expenses,
        ]);
    }
}
