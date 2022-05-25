<?php

namespace App\Controller;

use App\Entity\Categories;
use App\Entity\Interroger;
use App\Form\InterrogerType;
use App\Repository\InterrogerRepository;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Types\Integer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

/**
 * @Route("/admin/interroger")
 */
class InterrogerController extends AbstractController
{
    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    private function getSlugger(Interroger $interroger) : string{
        $slug = mb_strtolower($interroger->getIntitule() . '-' . time(), 'UTF8');
        return $this->slugger->slug($slug);                        
    }

    /**
     * @Route("/", name="app_interroger_index", methods={"GET"})
     */
    public function index(InterrogerRepository $interrogerRepository): Response
    {
        return $this->render('interroger/interroger_index.html.twig', [
            'interrogers' => $interrogerRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_interroger_new", methods={"GET", "POST"})
     */
    public function new(Request $request, InterrogerRepository $interrogerRepository,EntityManagerInterface $manager): Response
    {
        $interroger = new Interroger();
        $form = $this->createForm(InterrogerType::class, $interroger);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $interroger->setSlug($this->getSlugger($interroger));
            $interrogerRepository->add($interroger);
            $manager->persist($interroger);
            $manager->flush();

            $this->addFlash("success","La création a été effectuée");
            return $this->redirectToRoute('app_interroger_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('interroger/new.html.twig', [
            'interroger' => $interroger,
            'form' => $form->createView(),

        ]);
    }

    /**
     * @Route("/{slug}", name="app_interroger_show", methods={"GET"})
     */
    public function show(Interroger $interroger): Response
    {
        return $this->render('interroger/show.html.twig', [
            'interroger' => $interroger,
            'slug' => $interroger->getSlug(),
        ]);
    }

    /**
     * @Route("/{slug}/modifier", name="app_interroger_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Interroger $interroger, InterrogerRepository $interrogerRepository): Response
    {
        $form = $this->createForm(InterrogerType::class, $interroger);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $interrogerRepository->add($interroger);
            $this->addFlash("success","La modification a été effectuée");
            return $this->redirectToRoute('app_interroger_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('interroger/edit.html.twig', [
            'interroger' => $interroger,
            'form' => $form->createView(),
            'slug' => $interroger->getSlug(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_interroger_delete", methods={"POST"})
     */
    public function delete(Request $request, Interroger $interroger, InterrogerRepository $interrogerRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$interroger->getId(), $request->request->get('_token'))) {
            $interrogerRepository->remove($interroger);
            $this->addFlash("success","La suppression a été effectuée");
        }

        return $this->redirectToRoute('app_interroger_index', [], Response::HTTP_SEE_OTHER);
    }
}
