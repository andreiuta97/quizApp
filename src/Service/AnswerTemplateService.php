<?php


namespace QuizApp\Service;


use Framework\Contracts\SessionInterface;
use QuizApp\Repository\AnswerTemplateRepository;


class AnswerTemplateService
{
    private $answerTemplateRepo;
    private $session;

    public function __construct
    (
        AnswerTemplateRepository $answerTemplateRepo,
        SessionInterface $session
    )
    {
        $this->answerTemplateRepo = $answerTemplateRepo;
        $this->session = $session;
    }
}