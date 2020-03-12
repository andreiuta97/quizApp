<?php


namespace QuizApp\Service;


use Framework\Contracts\SessionInterface;
use QuizApp\Entity\QuizInstance;
use QuizApp\Entity\QuizTemplate;
use QuizApp\Repository\QuizInstanceRepository;
use QuizApp\Repository\UserRepository;
use ReallyOrm\Repository\RepositoryManagerInterface;

class QuizInstanceService
{
    private $repositoryManager;
    private $quizInstanceRepo;
    private $session;

    public function __construct
    (
        RepositoryManagerInterface $repositoryManager,
        QuizInstanceRepository $quizInstanceRepo,
        SessionInterface $session
    )
    {
        $this->repositoryManager = $repositoryManager;
        $this->quizInstanceRepo = $quizInstanceRepo;
        $this->session = $session;
    }

    public function createQuizInstance(int $quizTemplateId)
    {
        $userId = $this->session->get('id');
        $quizTemplateRepo = $this->repositoryManager->getRepository(QuizTemplate::class);
        $quizTemplate=$quizTemplateRepo->find($quizTemplateId);
        $quiz = new QuizInstance();
        $quiz->setName($quizTemplate->getName());
        $quiz->setType($quizTemplate->getType());
        $quiz->setScore(0);
        $quiz->setUserId($userId);
        $quiz->setQuizTemplateId($quizTemplateId);
        $this->quizInstanceRepo->insertOnDuplicateKeyUpdate($quiz);
        $this->session->set('quizInstanceId', $quiz->getId());

        return $quiz;
    }

    public function getQuestionsNumber(int $quizTemplateId):int
    {
        return $this->quizInstanceRepo->getQuestionsNumber($quizTemplateId);
    }

    public function getResultsData()
    {
        return $this->quizInstanceRepo->getResultsData();
    }
}