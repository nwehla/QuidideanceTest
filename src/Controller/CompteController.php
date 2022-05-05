<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\ChangePasswordType;
use App\Form\UtilisateurModifierType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\UtilisateurRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\DateTime;
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
     * @Route("/modifier-mon-mot-de-passe", name="compte_password")
     */
    public function modifierpassword(Request $request, UserPasswordHasherInterface $encoder): Response
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

    /**
     * @Route("/modifier-mon-compte", name="compte_edit", methods={"GET", "POST"})
     */
    public function modifiermesinfos(Request $request, Utilisateur $utilisateur, UtilisateurRepository $utilisateurRepository, EntityManagerInterface $manager): Response
    {
        // if ($this->isGranted($this->getUser())) {
        // $form = $this->createForm(UtilisateurModifierType::class, $utilisateur);
        // $form->handleRequest($request);

        // if ($form->isSubmitted() && $form->isValid()) {
        //     //initialisation de la date de creation 
        //     $utilisateur->setDatemiseajour(new DateTime());
            
        //     //Persist
        //     $manager->persist($utilisateur);
             
        //     //Flush
        //     $manager->flush();
        //     $utilisateurRepository->add($utilisateur);
        //     $this->addFlash("success","La modification a été effectuée");
        //     return $this->redirectToRoute('compte_index', [], Response::HTTP_SEE_OTHER);
        // }

        // return $this->render('compte/compte_edit.html.twig', [
        //     'utilisateur' => $utilisateur,
        //     'form' => $form->createView(),
        // ]);
        // }
        // else{
        //     return $this->redirectToRoute('app_accueil', [], Response::HTTP_SEE_OTHER);
        // }

        // //TEST
        $utilisateur = $this->getUser();
        dd($utilisateur);
        //Appel du formulaire
        $form = $this->createForm(UtilisateurModifierType::class , $utilisateur);

        //Traitement du formulaire
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
                //initialisation de la date de creation 
            
            //Persist
            $manager->persist($utilisateur);
             
            //Flush
            $manager->flush();              
            }
                
        return $this->render('compte/compte_edit.html.twig' , [
            'utilisateur'=> $utilisateur,
            'id' =>$utilisateur-> $this->getId(),
            // 'id' => $utilisateur->getId(),
            'form' => $form->createView(),
        ]);    
    }
}




// /**
//      * @Route("/profil/utilisateur/{slug}/modifier", name="profil_utilisateur_modifier", methods={"GET" , "POST"})
//      */

//     public function modifier(Request $request, Utilisateur $utilisateur, EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder, UtilisateurRepository $repo)
//     {
//         // Creation de mon Formulaire
//         $form = $this->createForm(UtilisateurType::class, $utilisateur);

//         // Analyse des Requetes & Traitement des information 
//         $form->handleRequest($request);

//         if ($form->isSubmitted() && $form->isValid()) {

//             $passwordcrypter = $encoder->encodePassword($utilisateur, $utilisateur->getPassword());
//             $utilisateur->setPassword($passwordcrypter);
//             // $manager->persist($utilisateur); 
//             $manager->flush();

//             return $this->redirectToRoute(
//                 'utilisateur_montre',
//                 ['slug' => $utilisateur->getSlug()]
//             ); // Redirection vers la page
//         }
//         // Redirection du Formulaire vers le TWIG pour l’affichage avec
//         return $this->render('profil/utilisateur_modification.html.twig', [
//             "utilisateur" => $utilisateur,

//             'formUtilisateur' => $form->createView(),

//             'slug' => $utilisateur->getSlug(),
//         ]);
//     }
