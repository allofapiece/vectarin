<?php

namespace App\Controller;


use App\Entity\User;
use App\Service\UserService\UserService;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
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
            2
        );

        return $this->render('users/users.html.twig', [
            'pagination' => $paginator,
        ]);
    }

    /**
     * @Route("/user/update-is-active/{id}",name="user.update.is_active")
     * @param Request $request
     * @param UserService $userService
     * @param $id
     * @return Response
     */
    public function updateUserIsActive(Request $request, UserService $userService, $id)
    {
        $user=$this->checkUserById($id);

        $userService->changeIsActive($user);

        $userService->executeQueryToDb($user);

        $referer = $request->headers->get('referer');

        return new RedirectResponse($referer);
    }

    /**
     * @Route("admin/user/delete/{id}", name="user.delete")
     * @param Request $request
     * @param UserService $userService
     * @param $id
     * @return RedirectResponse
     */
    public function deleteUser(Request $request, UserService $userService, $id)
    {
        $user = $this->checkUserById($id);

        $entityManager = $this->getDoctrine()->getManager();

        $entityManager->remove($user);

        $entityManager->flush();

        $referer = $request->headers->get('referer');

        return new RedirectResponse($referer);
    }

    /**
     * @param $id
     * @return User
     */
    public function checkUserById($id) :User
    {
        $user = $this->getDoctrine()
            ->getRepository(User::class)
            ->findOneBy(['id' => $id]);

        if (!$user) {
            throw $this->createNotFoundException('Данный пользователь не найден!');
        }

        if (in_array('ROLE_ADMIN',$user->getRoles())) {
            throw $this->createAccessDeniedException('Вы не можете менять данные администратора!');
        }

        return $user;
    }

}

