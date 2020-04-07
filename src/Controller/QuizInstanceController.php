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
use QuizApp\Service\Paginator;
use QuizApp\Service\QuestionInstanceService;
use QuizApp\Service\QuizInstanceService;
use ReallyOrm\Criteria\Criteria;
use ReallyOrm\Repository\RepositoryManagerInterface;

class QuizInstanceController extends AbstractController
{
    private const QUIZ_INSTANCE_ID = 'quiz_instance_id';

    const RESULTS_PER_PAGE = 5;

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

    public function __construct
    (
        RendererInterface $renderer,
        RepositoryManagerInterface $repositoryManager,
        QuizTemplateRepository $quizTemplateRepository,
        QuizInstanceService $quizInstanceService,
        QuestionInstanceService $questionInstanceService,
        Session $session
    ) {
        parent::__construct($renderer);
        $this->repositoryManager = $repositoryManager;
        $this->quizInstanceService = $quizInstanceService;
        $this->questionInstanceService = $questionInstanceService;
        $this->quizTemplateRepository = $quizTemplateRepository;
        $this->session = $session;
    }

    /**
     * Creates a quiz instance and redirects to the first question of the quiz.
     *
     * @param Request $request
     * @param array $requestAttributes
     * @return Response
     */
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

    private function getCriteriaFromRequest(array $requestAttributes): Criteria
    {
        $currentPage = $requestAttributes['page'] ?? 1;
        $from = ($currentPage - 1) * self::RESULTS_PER_PAGE;

        return new Criteria([], [], $from, self::RESULTS_PER_PAGE);
    }

    /**
     * Displays all quiz templates available to be displayed on the candidate Homepage.
     *
     * @param Request $request
     * @param array $requestAttributes
     * @return Response
     */
    public function getQuizzes(Request $request, array $requestAttributes): Response
    {
        $currentPage = $requestAttributes['page'] ?? 1;
        $criteria = $this->getCriteriaFromRequest($requestAttributes);
        $quizzesSearchResult = $this->quizInstanceService->getQuizzes($criteria);
        $paginator = new Paginator($quizzesSearchResult->getCount(), $currentPage, self::RESULTS_PER_PAGE);

        return $this->renderer->renderView('candidate-quiz-listing.phtml',
            ['quizzes' => $quizzesSearchResult->getItems(), 'paginator' => $paginator]);
    }

    /**
     * Displays the overview page of a particular quiz instance.
     *
     * @param Request $request
     * @param array $requestAttributes
     * @return Response
     */
    public function showOverview(Request $request, array $requestAttributes): Response
    {
        $quizInstanceId = $this->session->get(self::QUIZ_INSTANCE_ID);
        $quizInstance = $this->quizInstanceService->findQuiz($quizInstanceId);
        $answeredQuestions = $this->questionInstanceService->getAnsweredQuestions($quizInstanceId);

        return $this->renderer->renderView
        ('candidate-overview.phtml',
            [
                'quizInstance' => $quizInstance,
                'answeredQuestions' => $answeredQuestions,
            ]
        );
    }

    /**
     * Displays the 'Congrats' page after completing and saving a quiz.
     *
     * @param Request $request
     * @param array $requestAttributes
     * @return Response
     */
    public function showSuccess(Request $request, array $requestAttributes): Response
    {
        return $this->renderer->renderView('quiz-success-page.phtml', $requestAttributes);
    }
}