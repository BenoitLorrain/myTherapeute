<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\File;


class BlogController extends AbstractController
{
    public function __construct(EntityManagerInterface $manager, ManagerRegistry $doctrine)
    {
        $this->manager = $manager;
        $this->doctrine = $doctrine;
    }

    /**
    * @Route("/blog/single/{id}", name="app_single_article")
    */
    public function viewArticle($id): Response
    {

        $entityManager = $this->doctrine->getManager();
        $singleArticle = $entityManager->getRepository(Article::class)->findBy(['id'=> $id]);
        
        return $this->render('blog/single.html.twig', [
            'singleArticle' => $singleArticle[0],
        ]);
    }

    /**
    * @Route("/blog/list", name="app_blog_list")
    */
    public function listArticle(): Response
    {
        $entityManager = $this->doctrine->getManager();
        $articles = $entityManager->getRepository(Article::class)->findAll();
        return $this->render('blog/list.html.twig',
            ['articles' => $articles]
        );
    }

    /**
    * @Route("/blog/new", name="blog_create")
    */
    public function addArticle(Request $request,SluggerInterface $slugger): Response
    {

        $article = new Article(); // instantiation de l'entité Article
        $addArticle = $this->createForm(ArticleType::class, $article);
        $addArticle->handleRequest($request);

        
        if ($addArticle->isSubmitted() && $addArticle->isValid()) {
            $article->setPublishedAt(new \DateTime);
            $entityManager = $this->doctrine->getManager();
            $picture = $addArticle->get('picture')->getData();

            if ($picture) {
                $originalFilename = pathinfo($picture->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$picture->guessExtension();

                try {
                    $picture->move(
                        $this->getParameter('pictures_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                }

                $article->setPicture($newFilename);
            }



            $entityManager->persist($article);
            $entityManager->flush();

            return $this->redirectToRoute('admin');
        }

        return $this->render('blog/create.html.twig', [
            'addArticle' => $addArticle->createView()
        ]);
    }

    /**
    * @Route("/blog/remove/{id}", name="app_remove_article")
    */
    public function deleteArticle($id)
    {
        /*
        $entityManager = $this->doctrine->getManager();
        $singleArticle = $entityManager->getRepository(Article::class)->findBy(['id' => $id]);
        $entityManager->remove($singleArticle[0]);
        $entityManager->flush();
        */
        $singleArticle = $this->doctrine->getRepository(Article::class)->find($id);
        $entityManager = $this->doctrine->getManager();
        $entityManager->remove($singleArticle);
        $entityManager->flush();
        $this->addFlash('success', "L'article a bien été supprimé !");

        return $this->redirectToRoute('app_dashboard_article');
    }

    /**
    * @Route("/blog/update/{id}", name="app_update_article")
    */
    public function updateArticle($id, Request $request)
    {

        $entityManager = $this->doctrine->getManager();
        $singleArticle = $entityManager->getRepository(Article::class)->find($id);

        if (!$singleArticle) {
            throw $this->createNotFoundException('Article non trouvée pour l\'id '.$id);
        }

        $form = $this->createForm(ArticleType::class, $singleArticle);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {

            $pictureFile = $form->get('picture')->getData();

            if ($pictureFile) {
                $newFilename = uniqid().'.'.$pictureFile->guessExtension();
                $pictureFile->move(
                    $this->getParameter('pictures_directory'),
                    $newFilename
                );

                $singleArticle->setPicture($newFilename);
            }

            $entityManager->persist($singleArticle);
            $entityManager->flush();
            return $this->redirectToRoute('app_dashboard_article');
        }

        return $this->render('blog/update.html.twig', [
            'article' => $singleArticle,
            'form' => $form->createView()
        ]);

    }

}
