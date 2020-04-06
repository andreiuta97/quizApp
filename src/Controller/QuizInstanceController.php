<?php


namespace QuizApp\Controller;


use Framework\Contracts\RendererInterface;
use Framework\Controller\AbstractController;
use Framework\Http\Request;
use Framework\Http\Response;
use Framework\Http\Stream;
use Framework\Session\Session;
use QuizApp\Entity\QuestionInstance;
use QuizApp\Entity\QuizTemplate;
use QuizApp\Repository\QuizTemplateRepository;
use QuizApp\Service\CriteriaTrait;
use QuizApp\Service\Paginator;
use QuizApp\Service\QuestionInstanceService;
use QuizApp\Service\QuizInstanceService;
use ReallyOrm\Criteria\Criteria;
use ReallyOrm\Repository\RepositoryManagerInterface;

class QuizInstanceController extends AbstractController
{
    use CriteriaTrait;

    /**
     * @var RepositoryManagerInterface
     */
    private $repositoryManager;
    /**
     * @var QuizInstanceService
     */
    private $quizInstanceService;
    /**
     * @var QuestionInstanceService
     */
    private $questionInstanceService;
    /**
     * @var QuizTemplateRepository
     */
    private $quizTemplateRepository;
    /**
     * @var Session
     */
    private $session;
    /**
     * @var int
     */
    private $resultsPerPage;

    public function __construct
    (
        RendererInterface $renderer,
        RepositoryManagerInterface $repositoryManager,
        QuizTemplateRepository $quizTemplateRepository,
        QuizInstanceService $quizInstanceService,
        QuestionInstanceService $questionInstanceService,
        Session $session,
        int $resultsPerPage
    ) {
        parent::__construct($renderer);
        $this->repositoryManager = $repositoryManager;
        $this->quizInstanceService = $quizInstanceService;
        $this->questionInstanceService = $questionInstanceService;
        $this->quizTemplateRepository = $quizTemplateRepository;
        $this->session = $session;
        $this->resultsPerPage = $resultsPerPage;
    }

    public function startQuiz(Request $request, array $requestAttributes): Response
    {
        /** @var QuizTemplate $quizTemplate */
        $quizTemplate = $this->quizTemplateRepository->find($requestAttributes['id']);
        $quizInstance = $this->quizInstanceService->createQuizInstance($quizTemplate->getId());
        $this->questionInstanceService->createQuestionInstances($quizTemplate, $quizInstance);

        $body = Stream::createFromString('');
        $response = new Response($body, '1.1', 301, '');
        $response = $response->withHeader('Location', '/quiz/question/1');


        return $response;
    }

    public function getQuizzes(Request $request, array $requestAttributes): Response
    {
        $currentPage = $requestAttributes['page'] ?? 1;
        $criteria = $this->getCriteriaFromRequest($requestAttributes, $this->resultsPerPage);
        $quizzesSearchResult = $this->quizInstanceService->getQuizzes($criteria);
        $paginator = new Paginator($quizzesSearchResult->getCount(), $currentPage, $this->resultsPerPage);

        return $this->renderer->renderView('candidate-quiz-listing.phtml',
            ['quizzes' => $quizzesSearchResult->getItems(), 'paginator' => $paginator]);
    }

    public function getQuizStarted(Request $request, array $requestAttributes): Response
    {
        return $this->renderer->renderView('candidate-quiz-page.phtml', $requestAttributes);
    }

    public function showOverview(Request $request, array $requestAttributes): Response
    {
        $quizInstanceId = $this->session->get('quizInstanceId');
        $quizInstance = $this->quizInstanceService->findQuiz($quizInstanceId);
        $questionsAnswers = $this->questionInstanceService->getAllQuestionsForQuizInstance($quizInstanceId);

        return $this->renderer->renderView
        ('candidate-overview.phtml',
            [
                'quizInstance' => $quizInstance,
                'questionsAnswers' => $questionsAnswers,
            ]
        );
    }

    public function showSuccess(Request $request, array $requestAttributes): Response
    {
        return $this->renderer->renderView('quiz-success-page.phtml', $requestAttributes);
    }
}