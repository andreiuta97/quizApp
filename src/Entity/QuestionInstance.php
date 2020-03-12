<?php


namespace QuizApp\Entity;


use ReallyOrm\Entity\AbstractEntity;

class QuestionInstance extends AbstractEntity
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
     * @ORM type
     * @var string
     */
    private $type;
    /**
     * @ORM question_template_id
     * @var int
     */
    private $questionTemplateId;
    /**
     * @ORM quiz_instance_id
     * @var int
     */
    private $quizInstanceId;

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
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return int
     */
    public function getQuestionTemplateId(): int
    {
        return $this->questionTemplateId;
    }

    /**
     * @return int
     */
    public function getQuizInstanceId(): int
    {
        return $this->quizInstanceId;
    }

    /**
     * @param string $text
     */
    public function setText(string $text)
    {
        $this->text = $text;
    }

    /**
     * @param string $type
     */
    public function setType(string $type)
    {
        $this->type = $type;
    }

    /**
     * @param int $questionTemplateId
     */
    public function setQuestionTemplateId(int $questionTemplateId): void
    {
        $this->questionTemplateId = $questionTemplateId;
    }

    /**
     * @param int $quizInstanceId
     */
    public function setQuizInstanceId(int $quizInstanceId): void
    {
        $this->quizInstanceId = $quizInstanceId;
    }
}
