<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\RegistrationFormType;
use App\Repository\UtilisateurRepository;
use App\Security\EmailVerifier;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class RegistrationController extends AbstractController
{
    private $emailVerifier;

    public function __construct(EmailVerifier $emailVerifier, SluggerInterface $slugger)
    {
        $this->emailVerifier = $emailVerifier;
        $this->slugger = $slugger;
    }

    private function getSlugger(Utilisateur $user) : string{
        $slug = mb_strtolower($user->getUsername() . '-' . time(), 'UTF8');
        return $this->slugger->slug($slug);                        
    }

     /**
      * @Route("/admin/utilisateur/new", name="app_utilisateur_new", methods={"GET", "POST"})
      */
    public function register(Request $request, UserPasswordHasherInterface $encoder, EntityManagerInterface $entityManager): Response
    {

        if ($this->isGranted('ROLE_SUPERADMIN') ) {
            $user = new Utilisateur();

        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
             //Encodage du mot de passe
             $password = $encoder->hashPassword($user , $user->getPassword());
            
            $user->setPassword($password)
                ->setSlug($this->getSlugger($user));
           
            $entityManager->persist($user);
            $entityManager->flush();

            // generate a signed url and email it to the user
            $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user,
                (new TemplatedEmail())
                    ->from(new Address('nwehlav@gmail.com', '"Quidideance Contact"'))
                    ->to($user->getEmail())
                    ->subject('Please Confirm your Email')
                    ->htmlTemplate('registration/confirmation_email.html.twig')
            );
            // do anything else you need here, like send an email

            return $this->redirectToRoute('app_utilisateur_index');
        }

        
        return $this->render('utilisateur/new.html.twig', [
            'utilisateur' => $user,
            'form' => $form->createView(),
        ]);
    }
      else{
         return $this->redirectToRoute('app_accueil', [], Response::HTTP_SEE_OTHER);
       }
    }

    /**
     * @Route("/verify/email", name="app_verify_email")
     */
    public function verifyUserEmail(Request $request, TranslatorInterface $translator, UtilisateurRepository $utilisateurRepository): Response
    {
        $id = $request->get('id');

        if (null === $id) {
            return $this->redirectToRoute('app_register');
        }

        $user = $utilisateurRepository->find($id);

        if (null === $user) {
            return $this->redirectToRoute('app_register');
        }

        // validate email confirmation link, sets User::isVerified=true and persists
        try {
            $this->emailVerifier->handleEmailConfirmation($request, $user);
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('verify_email_error', $translator->trans($exception->getReason(), [], 'VerifyEmailBundle'));

            return $this->redirectToRoute('app_register');
        }

        // @TODO Change the redirect on success and handle or remove the flash message in your templates
        $this->addFlash('success', 'Your email address has been verified.');

        return $this->redirectToRoute('compte_password');
    }
}
