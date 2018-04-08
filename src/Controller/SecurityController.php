<?php

namespace App\Controller;

use App\Entity\RecoveryPassword\CreateEmailRequest;
use App\Entity\RecoveryPassword\CreateRecoveryPassword;
use App\Entity\User;
use App\Form\LoginType;
use App\Form\RecoveryPassword\RecoveryTypeEmail;
use App\Form\RecoveryPassword\RecoveryTypePassword;
use App\Form\SignupType;
use App\Service\GenerateToken;
use App\Service\MessageSender;
use App\Service\UserService\UserService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class SecurityController extends Controller
{
    /**
     * @Route("/login",name="login")
     *
     * @param Request $request
     * @param AuthenticationUtils $authenticationUtils
     * @return Response
     */
    public function login(Request $request, AuthenticationUtils $authenticationUtils)
    {
        $form = $this->createForm(LoginType::class, new User());

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            return $this->redirectToRoute('home');
        }

        $authenticationError = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'form' => $form->createView(),
            'authenticationError' => $authenticationError,
        ]);
    }


    /**
     * @Route("/signup",name="signup")
     * @param Request $request
     * @param UserService $userService
     * @param MessageSender $messageSender
     * @return Response
     */
    public function signup(Request $request, UserService $userService, MessageSender $messageSender)
    {
        $form = $this->createForm(SignupType::class, new User());

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $user = $form->getData();

            $userService->setDefaultValues($user);

            $userService->commit($user);

            $messageSender->sendConfirmationMessage($user);

            return $this->redirectToRoute('home');
        }

        return $this->render('security/signup.html.twig', [
            'form' => $form->createView()
        ]);
    }


    /**
     * @Route("/register/confirm/{token}",name="confirm")
     * @param $token
     * @param UserService $userService
     * @return Response
     */
    public function registerConfirm($token, UserService $userService)
    {
        $user = $this->getDoctrine()
            ->getRepository(User::class)
            ->findOneBy(['token' => $token]);

        if (!$user) {
            throw $this->createNotFoundException('Access denied!');
        }

        $userService->setUserActive($user);

        $userService->commit($user);

        return $this->redirect('/login');
    }


    /**
     * @Route("/recovery-password-1", name="recovery.password.email")
     *
     * @param Request $request
     * @param MessageSender $messageSender
     * @param GenerateToken $generateToken
     * @param UserService $userService
     * @return Response
     */
    public function sendMessageToRecoveryPassword(Request $request, MessageSender $messageSender, GenerateToken $generateToken, UserService $userService)
    {
        $form = $this->createForm(RecoveryTypeEmail::class, new CreateEmailRequest());

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $email = $form->getData();

            $user = $this->getDoctrine()
                ->getRepository(User::class)
                ->findOneBy(['email' => $email->getEmail()]);

            if ($user) {
                $user->setRecoveryToken($generateToken->generate($user->getPassword()));

                $userService->commit($user);

                $messageSender->sendRecoveryPasswordMessage($user);
            }
        }

        return $this->render('security/recovery_password_step_1.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/recovery-password-2/{token}",name="recovery.password")
     * @param Request $request
     * @param UserService $userService
     * @param $token
     * @return Response
     */
    public function passwordRecovery(Request $request, UserService $userService, $token)
    {
        $user = $this->getDoctrine()
            ->getRepository(User::class)
            ->findOneBy(['recoveryToken' => $token]);

        if (!$user) {
            throw $this->createNotFoundException('Access denied!');
        }

        $form = $this->createForm(RecoveryTypePassword::class, new CreateRecoveryPassword());

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $password = $form->getData();

            $user->setPlainPassword($password->getPlainPassword());

            $userService->setEncodePassword($user);

            $user->setRecoveryToken('');

            $userService->commit($user);

            return $this->redirect('/login');
        }

        return $this->render('security/recovery_password_step_2.html.twig', [
            'form' => $form->createView(),
            'token'=>$token,
        ]);
    }

}