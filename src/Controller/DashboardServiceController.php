<?php

namespace App\Controller;

use App\Entity\Service;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DashboardServiceController extends AbstractController
{
    public function __construct(EntityManagerInterface $manager, ManagerRegistry $doctrine)
    {
        $this->manager = $manager;
        $this->doctrine = $doctrine;
    }


    /**
     * @Route("/dashboard/service", name="app_dashboard_service")
     */
    public function index(): Response
    {
        $entityManager = $this->doctrine->getManager();
        $services = $entityManager->getRepository(Service::class)->findAll();
        return $this->render('dashboard_service/index.html.twig', [
            'controller_name' => 'DashboardServiceController',
            'services' => $services
        ]);
    }
}
