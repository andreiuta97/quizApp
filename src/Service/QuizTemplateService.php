<?php


namespace QuizApp\Service;

use Framework\Contracts\SessionInterface;
use QuizApp\Entity\QuizTemplate;
use QuizApp\Repository\QuizTemplateRepository;
use ReallyOrm\Criteria\Criteria;
use ReallyOrm\SearchResult\SearchResult;

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
        $createdBy = $this->session->get('id');

        $quiz = new QuizTemplate();
        $quiz->setName($info['name']);
        $quiz->setDescription($info['description']);
        $quiz->setCreatedBy($createdBy);

        $this->quizTemplateRepo->insertOnDuplicateKeyUpdate($quiz);

        $this->quizTemplateRepo->saveQuestionsForQuiz($quiz->getId(), $info['questions']);

    }

    public function getQuiz(int $id): QuizTemplate
    {
        /**@var QuizTemplate $quiz * */
        $quiz = $this->quizTemplateRepo->find($id);

        return $quiz;
    }

    public function getQuizzes(Criteria $criteria): SearchResult
    {
        return $this->quizTemplateRepo->findBySearch($criteria);
    }

    public function update(int $id, array $info)
    {
        $createdBy = $this->session->get('id');

        $quiz = $this->getQuiz($id);
        $quiz->setName($info['name']);
        $quiz->setDescription($info['description']);
        $quiz->setCreatedBy($createdBy);

        $this->quizTemplateRepo->insertOnDuplicateKeyUpdate($quiz);
        $this->quizTemplateRepo->saveQuestionsForQuiz($quiz->getId(), $info['questions']);
    }

    public function delete(int $id)
    {
        $quiz = $this->getQuiz($id);
        $this->quizTemplateRepo->delete($quiz);
    }
}