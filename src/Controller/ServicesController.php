<?php

namespace App\Controller;

use App\Entity\Service;
use App\Form\ServiceType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class ServicesController extends AbstractController
{
    public function __construct(EntityManagerInterface $manager, ManagerRegistry $doctrine)
    {
        $this->manager = $manager;
        $this->doctrine = $doctrine;
    }

    /**
     * @Route("/services/single/{id}", name="app_single_service")
     */
    public function viewService($id): Response
    {
        $entityManager = $this->doctrine->getManager();
        $singleService = $entityManager->getRepository(Service::class)->findBy(['id'=> $id]);
    
        return $this->render('services/single.html.twig', [
            'singleService' => $singleService[0],
        ]);
    }

    /**
     * @Route("/services/list", name="app_services_list")
     */
    public function listService(): Response
    {
        $entityManager = $this->doctrine->getManager();
        $services = $entityManager->getRepository(Service::class)->findAll();
        return $this->render('services/list.html.twig', ['services'=> $services]);
    }


    /**
     * @Route("/services/new", name="service_create")
     */
    
    public function addService(Request $request, SluggerInterface $slugger): Response
    {
        
        $service = new Service();
        $addService = $this->createForm(ServiceType::class, $service);
        $addService->handleRequest($request);

        if($addService->isSubmitted() && $addService->isValid()) {
            $service->setPublishedAt(new \DateTime);
            $entityManager = $this->doctrine->getManager();
            $picture = $addService->get('picture')->getData();

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

            $service->setPicture($newFilename);
        }

        $entityManager->persist($service);
        $entityManager->flush();

        return $this->redirectToRoute('admin');
        }

        return $this->render('services/create.html.twig', [
            'addService' => $addService->createView()
        ]);
    }


    /**
     * @Route("/services/remove/{id}", name="app_remove_service")
     */
    public function deleteService($id)
    {
        $singleService = $this->doctrine->getRepository(Service::class)->find($id);
        $entityManager = $this->doctrine->getManager();
        $entityManager->remove($singleService);
        $entityManager->flush();
        $this->addFlash('success', "La prestation a bien été supprimée");

        return $this->redirectToRoute('app_dashboard_service');
    }


    /*
    /**
     * @Route("/services", name="app_services_list")
     */
    /*
    public function index(): Response
    {
        return $this->render('services/services.html.twig', [
            'controller_name' => 'ServicesController',
        ]);
    }
    */
}
