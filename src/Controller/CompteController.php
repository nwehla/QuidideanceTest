<?php

namespace App\Controller;

use DateTime;
use App\Entity\Utilisateur;
use App\Form\ChangePasswordType;
use App\Form\CompteModifierType;
use App\Form\UtilisateurModifierType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\UtilisateurRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * @Route("/admin/compte")
 */
class CompteController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        // $this->user = $user;
    }

    /**
     * @Route("/", name="compte")
     */
    public function index(): Response
    {
        $utilisateur = $this->getUser();


        return $this->render('compte/compte_index.html.twig', [
            'utilisateur' => $utilisateur,
        ]);
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
        $form = $this->createForm(ChangePasswordType::class, $user);

        //Traitement du formulaire
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //Récupération du mot de passe
            $old_pwd = $form->get('old_password')->getData();
            if ($encoder->isPasswordValid($user, $old_pwd)) {
                //Nouveau mot de passe
                $new_pwd = $form->get('new_password')->getData();
                //Encodage du mot de passe
                $password = $encoder->hashPassword($user, $new_pwd);
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

        return $this->render('compte/compte_password.html.twig', [
            'form' => $form->createView(),
            'notification' => $notification
        ]);
    }

    //     /**
    //     * @Route("/montre", name="utilisateur_montre", methods={"GET"})
    //    */
    //   public function show(): Response
    //   {
    //     $utilisateur = $this->getUser();

    //         return $this->render("compte/mes_informations.html.twig", [

    //        'utilisateur'=>$utilisateur,
    //     //    dd($utilisateur),
    //         ]);  
    //     }      

    // /**
    //  * @Route("/modifier-mon-compte", name="compte_edit", methods={"GET", "POST"})
    //  */
    // public function modifiermesinfos(Request $request, Utilisateur $utilisateur, UtilisateurRepository $utilisateurRepository, EntityManagerInterface $manager): Response
    // {
    //     // if ($this->isGranted($this->getUser())) {
    //     // $form = $this->createForm(UtilisateurModifierType::class, $utilisateur);
    //     // $form->handleRequest($request);

    //     // if ($form->isSubmitted() && $form->isValid()) {
    //     //     //initialisation de la date de creation 
    //     //     $utilisateur->setDatemiseajour(new DateTime());

    //     //     //Persist
    //     //     $manager->persist($utilisateur);

    //     //     //Flush
    //     //     $manager->flush();
    //     //     $utilisateurRepository->add($utilisateur);
    //     //     $this->addFlash("success","La modification a été effectuée");
    //     //     return $this->redirectToRoute('compte_index', [], Response::HTTP_SEE_OTHER);
    //     // }

    //     // return $this->render('compte/compte_edit.html.twig', [
    //     //     'utilisateur' => $utilisateur,
    //     //     'form' => $form->createView(),
    //     // ]);
    //     // }
    //     // else{
    //     //     return $this->redirectToRoute('app_accueil', [], Response::HTTP_SEE_OTHER);
    //     // }

    //     // //TEST
    //     $utilisateur = $this->getUser();
    //     dd($utilisateur);
    //     //Appel du formulaire
    //     $form = $this->createForm(UtilisateurModifierType::class , $utilisateur);

    //     //Traitement du formulaire
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //             //initialisation de la date de creation 

    //         //Persist
    //         $manager->persist($utilisateur);

    //         //Flush
    //         $manager->flush();              
    //         }

    //     return $this->render('compte/compte_edit.html.twig' , [
    //         'utilisateur'=> $utilisateur,
    //         'id' =>$utilisateur-> $this->getId(),
    //         // 'id' => $utilisateur->getId(),
    //         'form' => $form->createView(),
    //     ]);    
    // }


    // /**
    //  *@Route("/{id}/modifier-mon-compte", name="compte_edit", methods={"GET", "POST"})
    //  */

    // public function modifier(Request $request, Utilisateur $utilisateur, EntityManagerInterface $manager,UtilisateurRepository $repo)
    // {
    //     // Creation de mon Formulaire
    //     $form = $this->createForm(UtilisateurModifierType::class,$utilisateur);        

    //     // Analyse des Requetes & Traitement des information 
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {


    //          $manager->flush();

    //          return $this->redirectToRoute(
    //              'utilisateur_montre',
    //              ['id' => $utilisateur->getId()]
    //          ); // Redirection vers la page
    //     }     
    //     // Redirection du Formulaire vers le TWIG pour l’affichage avec
    //     return $this->render('utilisateur/edit.html.twig', [
    //         "utilisateur" => $utilisateur,
    //         var_dump($utilisateur),
    //         'form' =>$form->createView(),

    //         'id'=>$utilisateur->getId(),
    //     ]);
    // }

    /**
     * @Route("/{id}/modifierCompte", name="compte_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Utilisateur $utilisateur, UtilisateurRepository $utilisateurRepository, EntityManagerInterface $manager,AuthenticationUtils $authenticationUtils): Response
    {
        $notification = null;
        $user = $this->getUser();

            $form = $this->createForm(CompteModifierType::class, $utilisateur);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {

                //Mettre à jour sur la base de 
                $this->entityManager->flush();

              
       
                //initialisation de la date de creation 
                $utilisateur->setDatemiseajour(new DateTime());

                //Persist
                $manager->persist($utilisateur);

                //Flush
                $manager->flush();
                $utilisateurRepository->add($utilisateur);
                $this->addFlash("success", "La modification a été effectuée");
                return $this->redirectToRoute('app_utilisateur_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('compte/formModifierCompte.html.twig', [
                'utilisateur' => $utilisateur,
                'formModifierCompte' => $form->createView(),
            ]);
       
        
    }
}
