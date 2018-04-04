<?php

namespace App\Service\UserService;


use App\Entity\User;
use App\Service\GenerateToken;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\TokenNotFoundException;

class UserService
{
    private $passwordEncoder;

    private $generateToken;

    private $entityManager;

    /**
     * UserService constructor.
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param GenerateToken $generateToken
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(UserPasswordEncoderInterface $passwordEncoder, GenerateToken $generateToken, EntityManagerInterface $entityManager)
    {
        $this->generateToken = $generateToken;
        $this->passwordEncoder = $passwordEncoder;
        $this->entityManager = $entityManager;
    }

    /**
     * @param User $user
     */
    public function setDefaultValues(User $user)
    {
        $this->setEncodePassword($user);
        $user->setToken($this->generateToken->generate($user->getEmail()));
        $user->setIsActive(0);
    }

    public function setEncodePassword(User $user)
    {
        $password = $this->passwordEncoder->encodePassword($user, $user->getPlainPassword());
        $user->setPassword($password);
    }

    /**
     * @param User $user
     */
    public function executeQueryToDb(User $user)
    {
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    /**
     * @param User $user
     */
    public function setUserActive(User $user)
    {
        $user->setIsActive(1);

        $user->setToken('');

        $user->setRoles(['ROLE_USER']);
    }
}