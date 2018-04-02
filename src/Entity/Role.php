<?php

namespace App\Entity;


use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\Role\RoleHierarchyInterface;
use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Table(name="roles")
 * @ORM\Entity
 */
class Role implements RoleHierarchyInterface
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * @ORM\Column(type="string",unique=true)
     */
    private $role;

    /**
     * @var ArrayCollection
     * @ORM\ManyToMany(targetEntity="User",mappedBy="roles")
     */
    private $users;

    public function __construct()
    {
        $this->users=new ArrayCollection();
    }

    public function getReachableRoles(array $roles)
    {
        // TODO: Implement getReachableRoles() method.
    }

    /**
     * @return mixed
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * @param mixed $role
     */
    public function setRole($role)
    {
        $this->role = $role;
    }
}