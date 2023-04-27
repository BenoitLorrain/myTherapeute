<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SecurityController extends AbstractController
{   
    /**
     * @Route("/connexion", name="security_login")
     */
    public function login()
    {
        return $this->render('security/login.html.twig', [
            'controller_name' => 'SecurityController',
        ]);
    }

    /**
    * @Route("/inscription", name="security_registration")
    */
    public function registration(Request $request, EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder)
    {
        $user = new User(); // on précise à quelle entité va être relié notre  formulaire
        $form = $this->createForm(RegisterType::class, $user); // on appel la classe qui permet de construire le formulaire
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid())
        {
        $hash = $encoder->encodePassword($user, $user->getPassword()); // on lui demande d'encoder le mot de passe et lui envoi un argument de type $user puisque c'est au moment de l'insertion d'un utilisateur que l'on veut crypter le mot de passe et en 2ème argument on lui envoi le champ 'password'
        $user->setPassword($hash); // on appel le setteur du mot pde passe et on lui demandede le hacher
        $user->setCreatedAt(new \DateTime);
        $user->setFullname($user->getLastname(). ' ' . $user->getFirstname());
        $manager->persist($user); // on fait persisiter dans le temps l'utilisateur, prépare toi à la sauvegarder
        $manager->flush(); // on lance la requete d'insertion
        return $this->redirectToRoute('security_login'); // on redirige vers la page login aprés inscription
        }

        return $this->render('register/index.html.twig', [
            'form' => $form->createView()
            ]);
    }

    /**
    * @Route("\deconnexion", name="security_logout")
    */
    public function logout(){
    // cette fonction ne retourne rien, il nous suffit d'avoir une route pour la deconnexion, une fois créer, modifier le providers form_login
    return $this->redirectToRoute('app_home');
    }


}
