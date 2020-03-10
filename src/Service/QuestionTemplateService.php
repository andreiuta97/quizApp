<?php


namespace QuizApp\Service;


use Framework\Contracts\SessionInterface;
use QuizApp\Entity\QuestionTemplate;
use QuizApp\Repository\QuestionTemplateRepository;

class QuestionTemplateService
{
    private $questionTemplateRepo;
    private $session;

    public function __construct
    (
        QuestionTemplateRepository $questionTemplateRepo,
        SessionInterface $session
    )
    {
        $this->questionTemplateRepo = $questionTemplateRepo;
        $this->session = $session;
    }

    public function add(array $info)
    {
        $question = new QuestionTemplate();
        $question->setText($info['text']);
        $question->setType($info['type']);

        $this->questionTemplateRepo->insertOnDuplicateKeyUpdate($question);
    }

    public function getQuestion(int $id)
    {
        return $this->questionTemplateRepo->find($id);
    }

    public function update(int $id, array $info)
    {
        $question = $this->getQuestion($id);
        $question->setText($info['text']);
        $question->setType($info['type']);

        $this->questionTemplateRepo->insertOnDuplicateKeyUpdate($question);
    }

    public function delete(int $id)
    {
        $question = $this->getQuestion($id);
        $this->questionTemplateRepo->delete($question);
    }
}