<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\LoginType;

use App\Form\SignupType;
use App\Service\GenerateToken;
use App\Service\MessageSender;
use App\Service\UserService\UserService;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class SecurityController extends Controller
{
    /**
     * @Route("/login",name="login")
     * @param Request $request
     * @param AuthenticationUtils $authenticationUtils
     * @return Response
     */
    public function login(Request $request, AuthenticationUtils $authenticationUtils)
    {

        $form = $this->createForm(LoginType::class, new User());

        $form->handleRequest($request);

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
        $user = new User();
        $form = $this->createForm(SignupType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $user = $form->getData();

            $userService->setDefaultValues($user);

            $userService->executeQueryToDb($user);

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
            throw $this->createNotFoundException('This user not found!');
        }

        $userService->setUserActive($user);

        $userService->executeQueryToDb($user);

        return $this->redirect('/login');
    }


    /**
     * @Route("/recovery", name="recovery")
     * @param Request $request
     * @return Response
     */
    public function passwordRecovery(Request $request)
    {
        return new Response();
    }

}