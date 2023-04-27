<?php

namespace App\Controller;
use App\Entity\Article;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class DashboardArticleController extends AbstractController
{
    public function __construct(EntityManagerInterface $manager, ManagerRegistry $doctrine)
    {
        $this->manager = $manager;
        $this->doctrine = $doctrine;
    }

    /**
     * @Route("/dashboard/article", name="app_dashboard_article")
     */
    public function index(): Response
    {
        $entityManager = $this->doctrine->getManager();
        $articles = $entityManager->getRepository(Article::class)->findAll();
        return $this->render('dashboard_article/index.html.twig', [
            'controller_name' => 'DashboardArticleController',
            'articles' => $articles
        ]);
    }
}
