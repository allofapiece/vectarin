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
        $user = $form->getData();
        $validateError = $validator->validate($user);

        $authenticationError = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'form' => $form->createView(),
            'authenticationError' => $authenticationError,
            'validateError' => $validateError,
        ]);
    }

    /**
     * @Route("/signup",name="signup")
     * @param Request $request
     * @return Response
     */
    public function signup(Request $request)
    {
        /*$user = new User();
        $user->setUsername("");
        $user->setPassword("");
        $user->setFirstname("");

        $form = $this->createForm(SignupType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            $user = $form->getData();

        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            $user = $form->getData();


            // ... perform some action, such as saving the task to the database
            // for example, if Task is a Doctrine entity, save it!
            // $entityManager = $this->getDoctrine()->getManager();
            // $entityManager->persist($task);
            // $entityManager->flush();

            return $this->redirectToRoute('quiz.show');
        }

        return $this->render('security/signup.html.twig',[
            'form' => $form->createView()
        ]);*/
        return new Response();
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