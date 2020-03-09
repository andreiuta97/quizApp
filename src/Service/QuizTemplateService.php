<?php


namespace QuizApp\Service;

use QuizApp\Entity\QuizTemplate;
use QuizApp\Repository\QuizTemplateRepository;

class QuizTemplateService
{
    private $quizTemplateRepo;

    public function __construct
    (
        QuizTemplateRepository $quizTemplateRepo
    )
    {
        $this->quizTemplateRepo = $quizTemplateRepo;
    }

    public function add(array $info)
    {
        $quiz = new QuizTemplate();
        $quiz->setName($info['text']);
        $quiz->setType($info['type']);

        $this->quizTemplateRepo->insertOnDuplicateKeyUpdate($quiz);
    }

    public function getQuiz(int $id)
    {
        return $this->quizTemplateRepo->find($id);
    }

    public function update(int $id, array $info)
    {
        $quiz = $this->getQuiz($id);
        $quiz->setName($info['text']);
        $quiz->setType($info['type']);

        $this->quizTemplateRepo->insertOnDuplicateKeyUpdate($quiz);
    }

    public function delete(int $id)
    {
        $quiz = $this->getQuiz($id);
        $this->quizTemplateRepo->delete($quiz);
    }
}