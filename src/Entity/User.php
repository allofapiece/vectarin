<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="app_users")
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User implements AdvancedUserInterface, \Serializable
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=25, unique=true)
     * @Assert\NotBlank(
     *     message = "Поле должно быть заполнено"
     * )
     * @Assert\Length(
     *     min = 4,
     *     max = 18,
     *     minMessage = "Длинна поля 'Логин' должна быть не меньше {{ limit }} ",
     *     maxMessage = "Длинна поля 'Логин' должна быть не больше {{ limit }} "
     * )
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private $password;

    /**
     * @Assert\NotBlank(
     *     message = "Поле должно быть заполнено"
     * )
     * @Assert\Length(max=4096)
     */
    private $plainPassword;

    /**
     * @ORM\Column(name="is_active", type="boolean")@Assert\Length(
     *     min = 4,
     *     max = 18,
     *     minMessage = "Длинна поля 'Пароль' должна быть не меньше {{ limit }} ",
     *     maxMessage = "Длинна поля 'Пароль' должна быть не больше {{ limit }} "
     * )
     * @Assert\Valid(
     *
     * )
     */
    private $isActive;


    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(
     *     message = "Поле должно быть заполнено"
     * )
     * @Assert\Length(
     *     min = 2,
     *     max = 50,
     *     minMessage = "Длинна поля 'Имя' должна быть не меньше {{ limit }} ",
     *     maxMessage = "Длинна поля 'Имя' должна быть не больше {{ limit }} "
     * )
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(
     *     message = "Поле должно быть заполнено"
     * )
     * @Assert\Email(
     *     message = "Поле должно соответствовать формату электронной почты"
     * )
     * @Assert\Length(
     *     min = 4,
     *     max = 50,
     *     minMessage = "Длинна поля 'email' должна быть не меньше {{ limit }} ",
     *     maxMessage = "Длинна поля 'email' должна быть не больше {{ limit }} "
     * )
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(
     *     message = "Поле должно быть заполнено"
     * )
     * @Assert\Length(
     *     min = 2,
     *     max = 50,
     *     minMessage = "Длинна поля 'Фамилия' должна быть не меньше {{ limit }} ",
     *     maxMessage = "Длинна поля 'Фамилия' должна быть не больше {{ limit }} "
     * )
     */
    private $surname;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(
     *     message = "Поле должно быть заполнено"
     * )
     * @Assert\Length(
     *     min = 4,
     *     max = 50,
     *     minMessage = "Длинна поля 'Отчество' должна быть не меньше {{ limit }} ",
     *     maxMessage = "Длинна поля 'Отчество' должна быть не больше {{ limit }} "
     * )
     */
    private $secondname;

    /**
     * @var array
     * @ORM\Column(type="json_array")
     */
    private $roles = [];

    /**
     * @ORM\Column(type="string",length=255)
     */
    private $token;


    public function __construct()
    {
        $this->isActive = true;
        $this->setUsername('');
        $this->setPassword('');
        $this->setFirstname('');
        $this->setEmail('');
        $this->setSecondname('');
        $this->setSurname('');
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getSalt()
    {
        // you *may* need a real salt depending on your encoder
        // see section on salt below
        return null;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function setRoles(array $roles)
    {
        $this->roles = $roles;
    }

    public function eraseCredentials()
    {
    }

    public function isAccountNonExpired()
    {
        return true;
    }

    public function isAccountNonLocked()
    {
        return true;
    }

    public function isCredentialsNonExpired()
    {
        return true;
    }

    public function isEnabled()
    {
        return $this->isActive;
    }

    /** @see \Serializable::serialize() */
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->username,
            $this->password,
            $this->isActive,
            // see section on salt below
            // $this->salt,
        ));
    }

    /** @see \Serializable::unserialize() */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->username,
            $this->password,
            $this->isActive,
            // see section on salt below
            // $this->salt
            ) = unserialize($serialized);
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname = null): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email = null): self
    {
        $this->email = $email;

        return $this;
    }

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setSurname(string $surname = null): self
    {
        $this->surname = $surname;

        return $this;
    }

    public function getSecondname(): ?string
    {
        return $this->secondname;
    }

    public function setSecondname(string $secondName = null): self
    {
        $this->secondname = $secondName;

        return $this;
    }

    public function setUsername(string $username = null): self
    {
        $this->username = $username;

        return $this;
    }

    public function setPassword(string $password = null): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return mixed
     */

    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    /**
     * @param mixed $plainPassword
     */
    public function setPlainPassword($plainPassword = null)
    {
        $this->plainPassword = $plainPassword;
    }

    /**
     * @return mixed
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @param mixed $token
     */
    public function setToken($token)
    {
        $this->token = $token;
    }

    /**
     * @param mixed $isActive
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;
    }


}