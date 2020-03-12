<?php


namespace QuizApp\Entity;


use ReallyOrm\Entity\AbstractEntity;

class QuizInstance extends AbstractEntity
{
    /**
     * @Identifier id
     * @var int
     */
    private $id;
    /**
     * @ORM name
     * @var string
     */
    private $name;
    /**
     * @ORM type
     * @var string
     */
    private $type;
    /**
     * @ORM score
     * @var int
     */
    private $score;
    /**
     * @ORM user_id
     * @var int
     */
    private $userId;
    /**
     * @ORM quiz_template_id
     * @var int
     */
    private $quizTemplateId;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return int
     */
    public function getScore(): int
    {
        return $this->score;
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * @return int
     */
    public function getQuizTemplateId(): int
    {
        return $this->quizTemplateId;
    }

    /**
     * @param string $name
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * @param string $type
     */
    public function setType(string $type)
    {
        $this->type = $type;
    }

    /**
     * @param int $score
     */
    public function setScore(int $score)
    {
        $this->score = $score;
    }

    public function setUserId(int $userId)
    {
        $this->userId = $userId;
    }

    public function setQuizTemplateId(int $quizTemplateId)
    {
        $this->quizTemplateId = $quizTemplateId;
    }
}