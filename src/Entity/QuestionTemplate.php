<?php


namespace QuizApp\Entity;


class QuestionTemplate
{
    private $id;
    private $text;
    private $type;

    public function __construct
    (
        int $id,
        string $text,
        string $type
    )
    {
        $this->id = $id;
        $this->text = $text;
        $this->type = $type;
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
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
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
}