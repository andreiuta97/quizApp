<?php


namespace QuizApp\Entity;


class QuizInstance extends QuizTemplate
{
    /**
     * @ORM score
     * @var int
     */
    private $score;

//    public function __construct(int $id, string $name, string $type, int $score)
//    {
//        parent::__construct($id, $name, $type);
//        $this->score = $score;
//    }

    /**
     * @return int
     */
    public function getScore(): int
    {
        return $this->score;
    }

    /**
     * @param int $score
     */
    public function setScore(int $score)
    {
        $this->score = $score;
    }
}