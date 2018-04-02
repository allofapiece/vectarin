<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\LoginType;

use App\Form\SignupType;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Validator\Validator\ValidatorInterface;


class SecurityController extends Controller
{
    /**
     * @Route("/login",name="login")
     * @param AuthenticationUtils $authenticationUtils
     * @return Response
     */
    public function login(Request $request, AuthenticationUtils $authenticationUtils, ValidatorInterface $validator)
    {
        $user = new User();
        $user->setUsername("");
        $user->setPassword("");
        $user->setFirstname("");

        $form = $this->createForm(LoginType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $user = $form->getData();

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
     * @return Response
     */
    public function signup(Request $request)
    {
        $user = new User();
        $user->setUsername("");
        $user->setPassword("");
        $user->setFirstname("");
        $user->setEmail("");
        $user->setSecondName("");
        $user->setSurname("");

        $form = $this->createForm(SignupType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();

            // $entityManager = $this->getDoctrine()->getManager();
            // $entityManager->persist($task);
            // $entityManager->flush();

            return $this->redirectToRoute('quiz.show');
        }

        return $this->render('security/signup.html.twig',[
            'form' => $form->createView()
        ]);
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