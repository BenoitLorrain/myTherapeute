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

class RegisterController extends AbstractController
{
    public function __construct(EntityManagerInterface $manager, UserPasswordHasherInterface $passwordHash)
    {
        $this->manager = $manager;
        $this->passwordHash = $passwordHash;
    }
    
    /**
     * @Route("/register", name="app_register")
     */
    public function index(Request $request): Response
    {
        $user = new User();
        $registerForm = $this->createForm(RegisterType::class, $user);
        $registerForm->handleRequest($request);

        if ($registerForm->isSubmitted() && $registerForm->isValid()) {
            $user->setCreatedAt(new \DateTime);

            $user->setFullname($user->getLastname(). ' ' . $user->getFirstname());
            $passwordEncod = $this->passwordHash->hashPassword($user , $user->getPassword());
            $user->setPassword($passwordEncod);

            $this->manager->persist($user);
            $this->manager->flush();
            return $this->redirectToRoute('app_home');
        }

        return $this->render('register/index.html.twig', [
            'registerForm' => $registerForm->createView()
        ]);
    }
}
