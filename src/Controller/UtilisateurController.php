<?php

namespace App\Controller;

use DateTime;
use App\Entity\Utilisateur;
use App\Form\UtilisateurType;
use App\Form\UtilisateurModifierType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\UtilisateurRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * @Route("/admin/utilisateur")
 */
class UtilisateurController extends AbstractController
{
    /**
     * @Route("/", name="app_utilisateur_index", methods={"GET"})
     */
    public function index(UtilisateurRepository $utilisateurRepository): Response
    {
        if ($this->isGranted('ROLE_SUPERADMIN')) {
        return $this->render('utilisateur/utilisateur_index.html.twig', [
            'utilisateurs' => $utilisateurRepository->findAll(),
        ]);
        }
        else{
            return $this->redirectToRoute('app_accueil', [], Response::HTTP_SEE_OTHER);
        }
    }

    /**
     * @Route("/new", name="app_utilisateur_new", methods={"GET", "POST"})
     */
    public function new(Request $request, UtilisateurRepository $utilisateurRepository, UserPasswordHasherInterface $encoder,EntityManagerInterface $manager): Response
    {
        if ($this->isGranted('ROLE_SUPERADMIN')) {
        $utilisateur = new Utilisateur();
        $form = $this->createForm(UtilisateurType::class, $utilisateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $utilisateur = $form->getData();
         
            //Encodage du mot de passe
            $password = $encoder->hashPassword($utilisateur , $utilisateur->getPassword());
            
            $utilisateur->setPassword($password);
            //initialisation de la date de creation 
            $utilisateur->setDatecreation(new DateTime());
            
            //Persist
            $manager->persist($utilisateur);
            
            //Flush
            $manager->flush(); 
            $utilisateurRepository->add($utilisateur);
            $this->addFlash("success","La création a été effectuée");
            return $this->redirectToRoute('app_utilisateur_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('utilisateur/new.html.twig', [
            'utilisateur' => $utilisateur,
            'form' => $form->createView(),
        ]);
        }
        else{
            return $this->redirectToRoute('app_accueil', [], Response::HTTP_SEE_OTHER);
        }
    }

    /**
     * @Route("/{id}", name="app_utilisateur_show", methods={"GET"})
     */
    public function show(Utilisateur $utilisateur): Response
    {
        if ($this->isGranted('ROLE_SUPERADMIN')) {
        return $this->render('utilisateur/show.html.twig', [
            'utilisateur' => $utilisateur,
        ]);
        }
        else{
            return $this->redirectToRoute('app_accueil', [], Response::HTTP_SEE_OTHER);
        }
    }

    /**
     * @Route("/{id}/edit", name="app_utilisateur_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Utilisateur $utilisateur, UtilisateurRepository $utilisateurRepository, EntityManagerInterface $manager): Response
    {
        if ($this->isGranted('ROLE_SUPERADMIN')) {
        $form = $this->createForm(UtilisateurModifierType::class, $utilisateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //initialisation de la date de creation 
            $utilisateur->setDatemiseajour(new DateTime());
            
            //Persist
            $manager->persist($utilisateur);
             
            //Flush
            $manager->flush();
            $utilisateurRepository->add($utilisateur);
            $this->addFlash("success","La modification a été effectuée");
            return $this->redirectToRoute('app_utilisateur_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('utilisateur/edit.html.twig', [
            'utilisateur' => $utilisateur,
            'form' => $form->createView(),
        ]);
        }
        else{
            return $this->redirectToRoute('app_accueil', [], Response::HTTP_SEE_OTHER);
        }
    }

    /**
     * @Route("/{id}", name="app_utilisateur_delete", methods={"POST"})
     */
    public function delete(Request $request, Utilisateur $utilisateur, UtilisateurRepository $utilisateurRepository): Response
    {
        if ($this->isGranted('ROLE_SUPERADMIN')) {
        if ($this->isCsrfTokenValid('delete'.$utilisateur->getId(), $request->request->get('_token'))) {
            $utilisateurRepository->remove($utilisateur);
            $this->addFlash("success","La suppression a été effectuée");
        }

        return $this->redirectToRoute('app_utilisateur_index', [], Response::HTTP_SEE_OTHER);
    }
    else{
        return $this->redirectToRoute('app_accueil', [], Response::HTTP_SEE_OTHER);
    }
    }
}
