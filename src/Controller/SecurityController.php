<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\LoginType;

use App\Form\SignupType;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
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

        $form = $this->createForm(LoginType::class, $user);

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
     * @return Response
     */
    public function signup(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $user = new User();
        $form = $this->createForm(SignupType::class, $user);

        // 2) handle the submit (will only happen on POST)
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // 3) Encode the password (you could also do this via Doctrine listener)

            $user=$form->getData();

            $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);

            // 4) save the User!
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            // ... do any other work - like sending them an email, etc
            // maybe set a "flash" success message for the user

            return $this->redirectToRoute('home');
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