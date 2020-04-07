<?php


namespace QuizApp\ViewModel;


use QuizApp\Entity\QuizInstance;
use QuizApp\Entity\User;

class UserQuizResult
{

    /**
     * @var $quizInstance QuizInstance
     */
    private $quizInstance;

    /**
     * @var $user User
     */
    private $user;

    /**
     * @return QuizInstance
     */
    public function getQuizInstance(): QuizInstance
    {
        return $this->quizInstance;
    }

    /**
     * @param QuizInstance $quizInstance
     */
    public function setQuizInstance(QuizInstance $quizInstance): void
    {
        $this->quizInstance = $quizInstance;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user): void
    {
        $this->user = $user;
    }
}