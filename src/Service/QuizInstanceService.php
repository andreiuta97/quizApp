<?php


namespace QuizApp\Service;


use Framework\Contracts\SessionInterface;
use QuizApp\Repository\QuizInstanceRepository;

class QuizInstanceService
{
    private $quizInstanceRepo;
    private $session;

    public function __construct
    (
        QuizInstanceRepository $quizInstanceRepo,
        SessionInterface $session
    )
    {
        $this->quizInstanceRepo = $quizInstanceRepo;
        $this->session = $session;
    }

//    public function getQuiz(int $id)
//    {
//        return $this->quizInstanceRepo->find($id);
//    }
}