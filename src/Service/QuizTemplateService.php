<?php


namespace QuizApp\Service;

use Framework\Contracts\SessionInterface;
use Framework\Http\Request;
use QuizApp\Entity\QuizTemplate;
use QuizApp\Repository\QuizTemplateRepository;

class QuizTemplateService
{
    private $quizTemplateRepo;
    private $session;

    public function __construct
    (
        QuizTemplateRepository $quizTemplateRepo,
        SessionInterface $session
    )
    {
        $this->quizTemplateRepo = $quizTemplateRepo;
        $this->session = $session;
    }

    public function add(array $info)
    {
        $this->session->start();
        $createdBy = $this->session->get('id');

        $quiz = new QuizTemplate();
        $quiz->setName($info['name']);
        $quiz->setType($info['type']);
        $quiz->setCreatedBy($createdBy);

        $this->quizTemplateRepo->insertOnDuplicateKeyUpdate($quiz);
    }

    public function getQuiz(int $id)
    {
        return $this->quizTemplateRepo->find($id);
    }

    public function update(int $id, array $info)
    {
        $this->session->start();
        $createdBy = $this->session->get('id');

        $quiz = $this->getQuiz($id);
        $quiz->setName($info['name']);
        $quiz->setType($info['type']);
        $quiz->setCreatedBy($createdBy);

        $this->quizTemplateRepo->insertOnDuplicateKeyUpdate($quiz);
    }

    public function delete(int $id)
    {
        $quiz = $this->getQuiz($id);
        $this->quizTemplateRepo->delete($quiz);
    }
}