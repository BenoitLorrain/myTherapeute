<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserController extends AbstractController
{
    public function __construct(EntityManagerInterface $manager, UserPasswordHasherInterface $passwordHash)
    {
        $this->manager = $manager;
        $this->passwordHash = $passwordHash;
    }

    /**
     * @Route("/user/remove/{id}", name="app_remove_user")
     */
    public function deleteUser($id)
    {
        $singleUser = $this->manager->getRepository(User::class)->findBy(['id' => $id]);
        $this->manager->remove($singleUser[0]);
        $this->manager->flush();
        $this->addFlash('success', "L'utilisateur a bien été supprimé !");

        return $this->redirectToRoute('app_dashboard_user');
    }

    /*
    /**
     * @Route("/user/update/{id}", name="app_edit_user")
     */
    /*
    public function updateUser($id, Request $request)
    {

        $singleUser = $this->manager->getRepository(User::class)->findBy(['id' => $id]);
        $singleUser[0]->setAvatar(null);

        $form = $this->createForm(RegisterType::class, $singleUser[0]);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $password = $form->get('password')->getData();
            //on récupère le champ password du formulaire
      
                $singleUser[0]->setPassword($this->passwordHash->hashPassword($singleUser[0] , $password));
            

            $this->manager->persist($singleUser[0]);
            $this->manager->flush();
            // Pour finir je fait une redirection
            return $this->redirectToRoute('app_dashboard');
        }


        return $this->render('user/update.html.twig', [
            'singleUser' => $singleUser[0],
            'form' => $form->createView()
        ]);
    }
    */
}

