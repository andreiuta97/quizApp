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
    /**
     * @var RepositoryManagerInterface
     */
    private $repositoryManager;

    /**
     * @var QuizInstanceRepository
     */
    private $quizInstanceRepo;

    /**
     * @var SessionInterface
     */
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

    /**
     * Creates a quiz instance by copying all properties of a quiz template.
     *
     * @param int $quizTemplateId
     * @return QuizInstance
     */
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
        $this->session->set('quiz_instance_id', $quiz->getId());

        return $quiz;
    }

    /**
     * Finds a quiz instance by its id.
     *
     * @param int $quizInstanceId
     * @return QuizInstance
     */
    public function findQuiz(int $quizInstanceId): QuizInstance
    {
        /** @var QuizInstance $quiz */
        $quiz = $this->quizInstanceRepo->find($quizInstanceId);

        return $quiz;
    }

    /**
     * Gets all quiz templates which comply to a specific Criteria, as a SearchResult.
     *
     * @param Criteria $criteria
     * @return SearchResult
     */
    public function getQuizzes(Criteria $criteria): SearchResult
    {
        return $this->repositoryManager->getRepository(QuizTemplate::class)->findBy($criteria);
    }

    /**
     * Gets all the necessary data to be displayed on the Results page.
     *
     * @return array
     */
    public function getResultsData(): array
    {
        return $this->quizInstanceRepo->getResultsData();
    }
}