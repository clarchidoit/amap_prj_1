<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use App\Security\UserAuthenticator;
use App\Service\JWTService;
use App\Service\SendMailService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
//use Symfony\Contracts\Translation\TranslatorInterface;

class RegistrationController extends AbstractController
{
    #[Route('/inscription', name: 'app_register')]
    public function register(
        Request $request,
        UserPasswordHasherInterface $userPasswordHasher,
        UserAuthenticatorInterface $userAuthenticator,
        UserAuthenticator $authenticator,
        EntityManagerInterface $entityManager,
        SendMailService $mail,
        JWTService $jwt
    ): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager->persist($user);
            $entityManager->flush();

            //on génère le JWT de l'utilisateur
            //on crée le header et le payload
            $header = ['typ' => 'JWT', 'alg' => 'HS256'];
            $payload =['user_id' => $user->getId()];
            //on génère le token
            $token = $jwt->generate($header, $payload, $this->getParameter('app.jwtsecret'));

            // do anything else you need here, like send an email
            //on envoie un mail
            $mail->send(
                'no-reply@monsite.net',
                $user->getEmail(),
                'Confirmation de votre email pour activation de votre compte Amap.',
                'register',
                compact('user', 'token')
            );

            return $userAuthenticator->authenticateUser(
                $user,
                $authenticator,
                $request
            );
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    #[Route('/verif/{token}', name: 'verify_user')]
    public function verifyUser(
        $token,
        JWTService $jwt,
        UserRepository $usersRepository,
        EntityManagerInterface $em,
    ): Response
    {
        //dd($jwt->check($token, $this->getParameter('app.jwtsecret')));

        //on vérifie si le token est valide, n'a pas expiré et n'a pas été modifié
        if(
            $jwt->isValid($token) &&
            !$jwt->isExpired($token)
            && $jwt->check($token, $this->getParameter('app.jwtsecret'))
        )
        {
            //on récupère le payload et le header
            $payload = $jwt->getPayload($token);
            $user = $usersRepository->find($payload['user_id']);

            //on vérifie que l'utilisateur existe et n'a pas encore validé son mail
            if($user && !$user->isIsMailValide()){
                $user->setIsMailValide(true);
                $em->flush($user);
                //$this->addFlash('success', 'Le compte a été créé et est en attente de validation par un responsable.');  //
                return $this->redirectToRoute('app_main');
            }

        }
        // ici un problème se pose dans le token
        //$this->addFlash('danger', 'Le token de confirmation de l\'email est invalide ou a expiré !');
        return $this->redirectToRoute('app_login');
    }

    #[Route('/renvoiverif', name: 'resend_verif')]
    public function resendVerif(
        JWTService $jwt,
        SendMailService $mail,
        UserRepository $userRepository
    ): Response
    {
        $user = $this->getUser();

        if(!$user){
            $this->addFlash('danger', 'Vous devez être connecté pour accéder à cette page.');
            return $this->redirectToRoute('app_main');
        }

        if($user->isIsMailValide()){
            $this->addFlash('warning', 'Votre email a déjà été vérifiée.');
            return $this->redirectToRoute('app_main');
        }

        //on génère le JWT de l'utilisateur
        //on crée le header et le payload
        $header = ['typ' => 'JWT', 'alg' => 'HS256'];
        $payload =['user_id' => $user->getId()];
        //on génère le token
        $token = $jwt->generate($header, $payload, $this->getParameter('app.jwtsecret'));
        //on envoie un mail
        $mail->send(
            'no-reply@monsite.net',
            $user->getEmail(),
            'Confirmation de votre email pour activation de votre compte Amap.',
            'register',
            compact('user', 'token')
        );
        return $this->redirectToRoute('app_main');
    }
}
