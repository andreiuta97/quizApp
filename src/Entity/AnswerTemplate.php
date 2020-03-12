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
    private $questionId;

//    public function __construct
//    (
//        int $id,
//        string $text
//    )
//    {
//        $this->id = $id;
//        $this->text = $text;
//    }

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

    public function getQuestionId(): int
    {
        return $this->questionId;
    }

    /**
     * @param string $text
     */
    public function setText(string $text)
    {
        $this->text = $text;
    }

    /**
     * @param int $questionId
     */
    public function setQuestionId(int $questionId): void
    {
        $this->questionId = $questionId;
    }

}