<?php


namespace QuizApp\Service;


use Framework\Contracts\SessionInterface;
use QuizApp\Repository\AnswerInstanceRepository;

class AnswerInstanceService
{
    private $answerInstanceRepo;
    private $session;

    public function __construct
    (
        AnswerInstanceRepository $answerInstanceRepo,
        SessionInterface $session
    )
    {
        $this->answerInstanceRepo = $answerInstanceRepo;
        $this->session = $session;
    }

    public function save(int $id, string $text)
    {
        $answer = $this->answerInstanceRepo->find($id);
        $answer->setText($text);

        $answer->save();
    }
}