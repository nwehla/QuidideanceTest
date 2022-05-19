<?php

namespace App\Controller;

use App\Form\TestType;
use App\Entity\Sondage;
use App\Entity\Interroger;
use App\Entity\Utilisateur;
use App\Repository\SondageRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\UtilisateurRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class TestformController extends AbstractController
{
    
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @Route("/testform", name="app_testform")
     */
    public function index(): Response
    {
        return $this->render('testform/index.html.twig', [
            'controller_name' => 'TestformController',
        ]);
    }

   /**
     * @Route("/testform/new", name="testform_sondage", methods={"GET", "POST"})
     */
    public function new(Request $request, SondageRepository $sondageRepository): Response
    {
        $sondage = new Sondage();
        $form = $this->createForm(TestType::class, $sondage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $sondageRepository->add($sondage);
            $this->addFlash("success","La création a été effectuée");
            return $this->redirectToRoute('app_testform', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('testform/new.html.twig', [
            'sondage' => $sondage,
            'form' => $form->createView(),
        ]);
    }



     /**
     * @Route("/{id}/testmodifierCompte", name="test_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Utilisateur $utilisateur, UtilisateurRepository $utilisateurRepository, EntityManagerInterface $manager,AuthenticationUtils $authenticationUtils): Response
    {
                
            $notification = null;
            $user = $this->getUser();                                                                                                                                                                                                                                                                                               
            //pour avoir l'id de l'utilisateur
            // dd($user);
            if ($this->isGranted($this->getUser()->getId())) {

                throw $this->redirectToRoute('app_accueil');
            }
            else{            

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
}
