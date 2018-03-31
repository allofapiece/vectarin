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

class UserController extends Controller
{
    /**
     * @Route("/admin/users", name="users")
     */
    public function signInAction(){
        return $this->render('users/users.html.twig');
    }

}