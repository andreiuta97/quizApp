<?php


namespace QuizApp\Service;


use QuizApp\Entity\User;
use QuizApp\Repository\UserRepository;

class UserService
{
    private $userRepo;

    public function __construct
    (
        UserRepository $userRepo
    )
    {
        $this->userRepo = $userRepo;
    }

    public function add(array $info)
    {
        $user = new User();
        $user->setName($info['name']);
        $user->setEmail($info['email']);
        $user->setPassword($info['password']);
        $user->setRole($info['role']);

        $this->userRepo->insertOnDuplicateKeyUpdate($user);
    }

    public function getUser(int $id)
    {
        return $this->userRepo->find($id);
    }

    public function update(int $id, array $info)
    {
        $user = $this->getUser($id);
        $user->setName($info['name']);
        $user->setEmail($info['email']);
        $user->setPassword($info['password']);
        $user->setRole($info['role']);

        $this->userRepo->insertOnDuplicateKeyUpdate($user);
    }

    public function delete(int $id)
    {
        $user = $this->getUser($id);
        $this->userRepo->delete($user);
    }
}