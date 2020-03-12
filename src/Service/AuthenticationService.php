<?php


namespace QuizApp\Service;


use Framework\Contracts\SessionInterface;
use QuizApp\Entity\User;
use QuizApp\Repository\UserRepository;

class AuthenticationService
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

    public function login(string $email, string $password)
    {
        $user = $this->userRepo->findOneBy(['email' => $email, 'password' => $password]);
        if (!$user) {
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
        if (!isset($_SESSION['id'])) {
            return null;
        }

        $id = $this->session->get('id');
        return $this->userRepo->find($id);
    }

//    public function getName()
//    {
//        return $this->session->get('name');
//    }

    public function logout()
    {
        $this->session->destroy();
    }
}