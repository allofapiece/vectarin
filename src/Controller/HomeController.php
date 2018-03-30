<?php
/**
 * Created by PhpStorm.
 * User: Listratsenka Stas
 * Date: 30.03.2018
 * Time: 17:41
 */

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HomeController extends Controller
{
    /**
     * @Route("/home/", name="home")
     */
    public function signInAction(){
        return $this->render('home/home.html.twig');
    }

    /**
     * Site path and unappropriate pathes
     * @Route("/")
     * @Route("/{url}")
     */
    public function otherAction(){
        return $this->redirectToRoute('home');
    }
}