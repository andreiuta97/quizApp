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
     * @ORM description
     * @var string
     */
    private $description;
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
     * @ORM is_completed
     * @var bool
     */
    private $isCompleted;

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
    public function getDescription(): string
    {
        return $this->description;
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
     * @return bool
     */
    public function isCompleted(): bool
    {
        return $this->isCompleted;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * @param int $score
     */
    public function setScore(int $score): void
    {
        $this->score = $score;
    }

    /**
     * @param int $userId
     */
    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
    }

    /**
     * @param int $quizTemplateId
     */
    public function setQuizTemplateId(int $quizTemplateId): void
    {
        $this->quizTemplateId = $quizTemplateId;
    }

    /**
     * @param bool $isCompleted
     */
    public function setIsCompleted(bool $isCompleted): void
    {
        $this->isCompleted = $isCompleted;
    }
}