<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DashboardUserController extends AbstractController
{
    public function __construct(EntityManagerInterface $manager, ManagerRegistry $doctrine)
    {
        $this->manager = $manager;
        $this->doctrine = $doctrine;
    }

    /**
     * @Route("/dashboard/user", name="app_dashboard_user")
     */
    public function index(): Response
    {
        $entityManager = $this->doctrine->getManager();
        $users = $entityManager->getRepository(User::class)->findAll();
        return $this->render('dashboard_user/index.html.twig', [
            'controller_name' => 'DashboardUserController',
            'users' => $users
        ]);
    }
}
