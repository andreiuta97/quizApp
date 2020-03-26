<?php


namespace QuizApp\Service;


use Framework\Contracts\SessionInterface;
use QuizApp\Entity\User;
use QuizApp\Repository\UserRepository;
use ReallyOrm\Criteria\Criteria;
use ReallyOrm\SearchResult\SearchResult;

class UserService
{
    private $userRepo;
    private $session;
    /**
     * @var HashingService
     */
    private $hashingService;

    public function __construct
    (
        UserRepository $userRepo,
        SessionInterface $session,
        HashingService $hashingService
    )
    {
        $this->userRepo = $userRepo;
        $this->session = $session;
        $this->hashingService = $hashingService;
    }

    public function add(array $info)
    {
        $user = new User();
        $user->setName($info['name']);
        $user->setEmail($info['email']);
        $user->setPassword($this->hashingService->hash($info['password']));
        $user->setRole($info['role']);

        $this->userRepo->insertOnDuplicateKeyUpdate($user);
    }

    public function getUser(int $id): User
    {
        /**@var User $user * */
        $user = $this->userRepo->find($id);

        return $user;
    }

    public function getUsers(Criteria $criteria): SearchResult
    {
        return $this->userRepo->findBy($criteria);
    }

    public function getUsersNumber(array $filters): int
    {
        $criteria = new Criteria($filters);

        return $this->userRepo->getNumberOfObjects($criteria);
    }

    public function update(int $id, array $info)
    {
        $user = $this->getUser($id);
        $user->setName($info['name']);
        $user->setEmail($info['email']);
        $user->setPassword($this->hashingService->hash($info['password']));
        $user->setRole($info['role']);

        $this->userRepo->insertOnDuplicateKeyUpdate($user);
    }

    public function delete(int $id)
    {
        $user = $this->getUser($id);
        $this->userRepo->delete($user);
    }
}