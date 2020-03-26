<?php


namespace QuizApp\Service;


use Framework\Contracts\SessionInterface;
use QuizApp\Entity\User;
use QuizApp\Repository\UserRepository;

class AuthenticationService
{
    /**
     * @var UserRepository
     */
    private $userRepo;

    /**
     * @var SessionInterface
     */
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

    public function login(string $email, string $password)
    {
        $user = $this->userRepo->findOneBy(['email' => $email]);
        if (!$user) {
            // TODO throw exception
            return '';
        }
        if(!$this->hashingService->verify($password, $user->getPassword())){
            // TODO throw exception
            return '';
        }
        $this->session->set('id', $user->getId());
        $this->session->set('name', $user->getName());
        $this->session->set('email', $user->getEmail());
        $this->session->set('role', $user->getRole());
        $this->session->get('name');

        return $user->getRole();
    }

    public function getLoggedUser(): ?User
    {
        $id = $this->session->get('id');
        if (!isset($id)) {
            return null;
        }
        /** @var User $user */
        $user = $this->userRepo->find($id);

        return $user;
    }

    public function logout()
    {
        $this->session->destroy();
    }
}