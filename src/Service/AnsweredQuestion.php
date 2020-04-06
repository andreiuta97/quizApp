<?php


namespace QuizApp\Service;


use QuizApp\Entity\AnswerInstance;
use QuizApp\Entity\QuestionInstance;

class AnsweredQuestion
{
    /**
     * @var QuestionInstance
     */
    private $question;
    /**
     * @var AnswerInstance
     */
    private $answer;

    /**
     * @param QuestionInstance $question
     */
    public function setQuestion(QuestionInstance $question): void
    {
        $this->question = $question;
    }

    /**
     * @param AnswerInstance $answer
     */
    public function setAnswer(AnswerInstance $answer): void
    {
        $this->answer = $answer;
    }

    /**
     * @return QuestionInstance
     */
    public function getQuestion(): QuestionInstance
    {
        return $this->question;
    }

    /**
     * @return AnswerInstance
     */
    public function getAnswer(): AnswerInstance
    {
        return $this->answer;
    }
}