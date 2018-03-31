<?php
/**
 * Created by PhpStorm.
 * User: Listratsenka Stas
 * Date: 27.03.2018
 * Time: 20:34
 */

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends Controller
{
    /**
     * @Route("/signin", name="signIn")
     */
    public function signInAction(){
        return $this->render('security/signin.html.twig');
    }

    /**
     * @Route("/login", name="login")
     */
    public function loginAction(Request $request, AuthenticationUtils $authenticationUtils)
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', array(
            'last_username' => $lastUsername,
            'error'         => $error,
        ));
    }

    /**
     * @Route("/recovery", name="recovery")
     */
    public function passwordRecoveryAction(){

    }

    /**
     * @Route("/admin", name="admin")
     */
    public function adminAction(){
        return new Response();
    }
}