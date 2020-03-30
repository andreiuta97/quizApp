<?php


namespace QuizApp\Service;


use Framework\Contracts\SessionInterface;
use QuizApp\Entity\QuizInstance;
use QuizApp\Entity\QuizTemplate;
use QuizApp\Repository\QuizInstanceRepository;
use ReallyOrm\Criteria\Criteria;
use ReallyOrm\Repository\RepositoryManagerInterface;
use ReallyOrm\SearchResult\SearchResult;

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

    public function createQuizInstance(int $quizTemplateId): QuizInstance
    {
        $userId = $this->session->get('id');
        $quizTemplateRepo = $this->repositoryManager->getRepository(QuizTemplate::class);
        $quizTemplate = $quizTemplateRepo->find($quizTemplateId);
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

    public function findQuiz(int $id): QuizInstance
    {
        /** @var QuizInstance $quiz */
        $quiz = $this->quizInstanceRepo->find($id);

        return $quiz;
    }

    public function getQuizzes(Criteria $criteria): SearchResult
    {
        return $this->repositoryManager->getRepository(QuizTemplate::class)->findBy($criteria);
    }

    public function getQuestionsNumber(int $quizTemplateId): int
    {
        return $this->quizInstanceRepo->getQuestionsNumber($quizTemplateId);
    }

    public function getResultsData(): array
    {
        return $this->quizInstanceRepo->getResultsData();
    }
}