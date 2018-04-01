<?php

namespace App\Controller;


use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class UserController
{
    /**
     * @Route("/admin/users",name="user.list")
     * @return Response
     */
    public function listUsers()
    {
        return new Response();
    }

    /**
     * @Route("/user/{id}/update",name="user.update")
     * @return Response
     */
    public function updateUser()
    {
        return new Response();
    }

}

