<?php


namespace QuizApp\Entity;


class AnswerTemplate
{
    private $id;
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