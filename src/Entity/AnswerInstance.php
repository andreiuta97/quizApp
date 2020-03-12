<?php


namespace QuizApp\Entity;


use ReallyOrm\Entity\AbstractEntity;

class AnswerInstance extends AbstractEntity
{
    /**
     * @Identifier id
     * @var int
     */
    private $id;
    /**
     * @ORM text
     * @var string
     */
    private $text;
    /**
     * @ORM question_instance_id
     * @var int
     */
    private $questionInstanceId;

    /**
     * @return int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * @return int
     */
    public function getQuestionInstanceId(): int
    {
        return $this->questionInstanceId;
    }

    /**
     * @param string $text
     */
    public function setText(string $text)
    {
        $this->text = $text;
    }

    /**
     * @param int $questionInstanceId
     */
    public function setQuestionInstanceId(int $questionInstanceId): void
    {
        $this->questionInstanceId = $questionInstanceId;
    }
}