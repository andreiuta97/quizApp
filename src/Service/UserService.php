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

    public function getFilteredUsersForPage(array $filters, int $currentPage): array
    {
        $paginator = new Paginator($this->getFilteredUsersNumber($filters));
        $criteria = new Criteria($filters, [], ($currentPage - 1) * $paginator->getResultsPerPage(), $paginator->getResultsPerPage());

        return $this->userRepo->findBy($criteria);
    }

    public function getFilteredUsersNumber(array $filters): int
    {
        $criteria = new Criteria($filters);

        return $this->userRepo->getNumberOfObjects($criteria);
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