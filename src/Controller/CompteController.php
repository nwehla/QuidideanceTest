<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ChangePasswordType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
     * @Route("/admin/compte")
     */
class CompteController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager) {
        $this->entityManager = $entityManager;
        // $this->user = $user;
    }

    
    /**
     * @Route("/", name="compte")
     */
    public function index(): Response
    {
        return $this->render('compte/compte_index.html.twig');
    }
    /**
     * @Route("/modifier", name="compte_password")
     */
    public function modifier(Request $request, UserPasswordHasherInterface $encoder): Response
    {
        $notification = null;
        //Récupération de l'utilisateur
        $user = $this->getUser();
        //$user = $this->user;
        //Appel du formulaire
        $form = $this->createForm(ChangePasswordType::class , $user);

        //Traitement du formulaire
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //Récupération du mot de passe
            $old_pwd = $form->get('old_password')->getData();
            if ($encoder->isPasswordValid($user , $old_pwd)) {
                //Nouveau mot de passe
                $new_pwd = $form->get('new_password')->getData();
                //Encodage du mot de passe
                $password = $encoder->hashPassword($user , $new_pwd);
                //Set du nouveau mot de passe
                $user->setPassword($password);

                //Mettre à jour sur la base de 
                $this->entityManager->flush();

                //Notification pour dire que le mot de passe est bien changé
                $notification = "Votre mot de passe a bien été mis à jour.";
            }
        } else {
            $notification = "Votre mot de passe actuel n'est pas le bon.";
        }
        
        return $this->render('compte/compte_password.html.twig' , [
            'form' => $form->createView(),
            'notification' => $notification
        ]);
    }
}
