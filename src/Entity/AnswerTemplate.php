<?php


namespace QuizApp\Entity;


use ReallyOrm\Entity\AbstractEntity;

class AnswerTemplate extends AbstractEntity
{
    /**
     * @ORM id
     * @var int
     */
    private $id;
    /**
     * @ORM text
     * @var string
     */
    private $text;

    public function __construct
    (
        int $id,
        string $text
    )
    {
        $this->id = $id;
        $this->text = $text;
    }

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
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * @param string $text
     */
    public function setText(string $text)
    {
        $this->text = $text;
    }

}