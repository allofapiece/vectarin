<?php
/**
 * Created by PhpStorm.
 * User: Listratsenka Stas
 * Date: 27.03.2018
 * Time: 20:34
 */

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

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
    public function loginAction(){
        return $this->render('security/login.html.twig');
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