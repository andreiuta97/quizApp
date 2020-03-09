<?php


namespace QuizApp\Service;


use QuizApp\Repository\UserRepository;

class AuthenticityService
{
    private $userRepo;

    public function __construct(UserRepository $userRepo)
    {
        $this->userRepo = $userRepo;
    }
}