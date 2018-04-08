<?php

namespace App\Controller;

use App\Entity\Answer;
use App\Entity\Question;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HomeController extends Controller
{
    /**
     * @Route("/",name="home.index")
     */
    public function indexAction()
    {
        return $this->redirectToRoute('quiz.show');
    }

    /**
     * @Route("/home",name="home")
     */
    public function home()
    {
        return $this->redirectToRoute('quiz.show');
    }
}