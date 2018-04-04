<?php

namespace App\Controller;


use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    /**
     * @Route("/admin/users",name="user.list")
     * @param Request $request
     * @param PaginatorInterface $paginator
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function listUsers(Request $request, PaginatorInterface $paginator, EntityManagerInterface $entityManager)
    {

        $dql = "SELECT u FROM App\Entity\User u";

        $query = $entityManager->createQuery($dql);

        $paginator = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            5
        );

        return $this->render('users/users.html.twig', [
            'pagination' => $paginator,
        ]);
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

