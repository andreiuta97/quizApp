<?php


namespace QuizApp\Service;


use Framework\Contracts\SessionInterface;
use QuizApp\Entity\User;
use QuizApp\Repository\UserRepository;
use ReallyOrm\Criteria\Criteria;

class UserService
{
    private $userRepo;
    private $session;

    public function __construct
    (
        UserRepository $userRepo,
        SessionInterface $session
    )
    {
        $this->userRepo = $userRepo;
        $this->session = $session;
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

    public function getUser(int $id): User
    {
        /**@var User $user * */
        $user = $this->userRepo->find($id);

        return $user;
    }

    public function getUsers(array $filters, int $currentPage): array
    {
        $criteria = new Criteria($filters, [], ($currentPage - 1) * 5, 5);
        return $this->userRepo->findBy($criteria);
    }

    public function getUserNumber(array $filters): int
    {
        $criteria = new Criteria($filters);
        return $this->userRepo->getNumberOfUsers($criteria);
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