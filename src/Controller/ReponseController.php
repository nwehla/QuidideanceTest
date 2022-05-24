<?php

namespace App\Controller;

use DateTime;
use App\Entity\Reponse;
use App\Form\ReponseType;
use App\Repository\ReponseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/admin/reponse")
 */
class ReponseController extends AbstractController
{
    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    private function getSlugger(Reponse $reponse) : string{
        $slug = mb_strtolower($reponse->getCommentaire() . '-' . time(), 'UTF8');
        return $this->slugger->slug($slug);                        
    }
    
    /**
     * @Route("/", name="app_reponse_index", methods={"GET"})
     */
    public function index(ReponseRepository $reponseRepository): Response
    {
        return $this->render('reponse/reponse_index.html.twig', [
            'reponses' => $reponseRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_reponse_new", methods={"GET", "POST"})
     */
    public function new(Request $request, ReponseRepository $reponseRepository,EntityManagerInterface $manager): Response
    {
        $reponse = new Reponse();
        $form = $this->createForm(ReponseType::class, $reponse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $reponse->setSlug($this->getSlugger($reponse))
                    ->setDatecreation(new DateTime());            
            //Persist
            $manager->persist($reponse);
            
            //Flush
            $manager->flush(); 
            $reponseRepository->add($reponse);
            $this->addFlash("success","La création a été effectuée");
            return $this->redirectToRoute('app_reponse_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('reponse/new.html.twig', [
            'reponse' => $reponse,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{slug}", name="app_reponse_show", methods={"GET"})
     */
    public function show(Reponse $reponse): Response
    {
        return $this->render('reponse/show.html.twig', [
            'reponse' => $reponse,
            'slug' => $reponse->getSlug(),
        ]);
    }

    /**
     * @Route("/{slug}/modifier", name="app_reponse_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Reponse $reponse, ReponseRepository $reponseRepository, EntityManagerInterface $manager): Response
    {
        $form = $this->createForm(ReponseType::class, $reponse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $reponse->setSlug($this->getSlugger($reponse))
                    ->setDatemiseajour(new DateTime());          
            //Persist
            $manager->persist($reponse);
            
            //Flush
            $manager->flush();
            $reponseRepository->add($reponse);
            $this->addFlash("success","La modification a été effectuée");
            return $this->redirectToRoute('app_reponse_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('reponse/edit.html.twig', [
            'reponse' => $reponse,
            'form' => $form->createView(),
            'slug' => $reponse->getSlug(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_reponse_delete", methods={"POST"})
     */
    public function delete(Request $request, Reponse $reponse, ReponseRepository $reponseRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reponse->getId(), $request->request->get('_token'))) {
            $reponseRepository->remove($reponse);
            $this->addFlash("success","La suppression a été effectuée");
        }

        return $this->redirectToRoute('app_reponse_index', [], Response::HTTP_SEE_OTHER);
    }
}
