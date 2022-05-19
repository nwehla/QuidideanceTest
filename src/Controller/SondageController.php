<?php

namespace App\Controller;

use App\Entity\Sondage;
use App\Form\SondageType;
use App\Entity\Interroger;
use App\Repository\SondageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/admin/sondage")
 */
class SondageController extends AbstractController
{
    /**
     * @Route("/", name="app_sondage_index", methods={"GET"})
     */
    public function index(SondageRepository $sondageRepository): Response
    {
        return $this->render('sondage/sondage_index.html.twig', [
            'sondages' => $sondageRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_sondage_new", methods={"GET", "POST"})
     */
    public function new(Request $request, SondageRepository $sondageRepository, EntityManagerInterface $manager): Response
    {
        $interroger = new Interroger();
        $sondage = new Sondage();        
        $form = $this->createForm(SondageType::class, $sondage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //Persist
            $manager->persist($sondage);
             
            //Flush
            $manager->flush();
            $sondageRepository->add($sondage);
            $this->addFlash("success","La création a été effectuée");
            return $this->redirectToRoute('app_sondage_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('sondage/new.html.twig', [
            'sondage' => $sondage,
            'statut' => $sondage->getStatut(),

            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_sondage_show", methods={"GET"})
     */
    public function show(Sondage $sondage): Response
    {
        return $this->render('sondage/show.html.twig', [
            'sondage' => $sondage,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_sondage_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Sondage $sondage, SondageRepository $sondageRepository): Response
    {
        $form = $this->createForm(SondageType::class, $sondage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $sondageRepository->add($sondage);
            $this->addFlash("success","La modification a été effectuée");
            return $this->redirectToRoute('app_sondage_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('sondage/edit.html.twig', [
            'sondage' => $sondage,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_sondage_delete", methods={"POST"})
     */
    public function delete(Request $request, Sondage $sondage, SondageRepository $sondageRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$sondage->getId(), $request->request->get('_token'))) {
            $sondageRepository->remove($sondage);
            $this->addFlash("success","La suppression a été effectuée");
        }

        return $this->redirectToRoute('app_sondage_index', [], Response::HTTP_SEE_OTHER);
    }
}
