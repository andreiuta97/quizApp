<?php


namespace QuizApp\Entity;


use ReallyOrm\Entity\AbstractEntity;

class AnswerTemplate extends AbstractEntity
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
     * @ORM question_template_id
     * @var int
     */
    private $questionTemplateId;

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
    public function getQuestionTemplateId(): int
    {
        return $this->questionTemplateId;
    }

    /**
     * @param string $text
     */
    public function setText(string $text): void
    {
        $this->text = $text;
    }

    /**
     * @param int $questionTemplateId
     */
    public function setQuestionTemplateId(int $questionTemplateId): void
    {
        $this->questionTemplateId = $questionTemplateId;
    }

}