<?php


namespace FreedomSex\AuthBundle\Security;


use Symfony\Component\Security\Core\User\UserInterface;

class JWTUser implements UserInterface
{
    private $uid;
    private $username;
    private $roles = [];

    public function __construct($uid, $uuid, array $data)
    {
        $this->uid = $uid;
        $this->uuid = $uuid;
        $this->username = $uid; // $data['username'];
        $this->roles = $data['roles'] ?? [];
    }

    public function getId()
    {
        return $this->uid;
    }

    public function getUuid()
    {
        return $this->uuid;
    }

    /**
     * {@inheritdoc}
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * {@inheritdoc}
     */
    public function getRoles(): ?array
    {
        $roles = $this->roles ?? [];
        $roles[] = 'ROLE_USER';
        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getPassword()
    {
    }

    /**
     * {@inheritdoc}
     */
    public function getSalt()
    {
    }

    /**
     * {@inheritdoc}
     */
    public function eraseCredentials()
    {
    }
}
