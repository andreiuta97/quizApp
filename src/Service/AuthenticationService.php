<?php


namespace QuizApp\Service;


use Framework\Contracts\SessionInterface;
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

    public function login()
    {

    }
}