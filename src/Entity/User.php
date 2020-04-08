<?php

namespace QuizApp\Entity;

use ReallyOrm\Entity\AbstractEntity;

class User extends AbstractEntity
{
    /**
     * @Identifier id
     * @var int
     */
    private $id;
    /**
     * @ORM name
     * @var string
     */
    private $name;
    /**
     * @ORM email
     * @var string
     */
    private $email;
    /**
     * @ORM password
     * @var string
     */
    private $password;
    /**
     * @ORM role
     * @var string
     */
    private $role;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @return string
     */
    public function getRole(): string
    {
        return $this->role;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    /**
     * @param string $role
     */
    public function setRole(string $role): void
    {
        $this->role = $role;
    }
}
