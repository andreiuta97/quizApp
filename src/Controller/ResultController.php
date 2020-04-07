<?php


namespace QuizApp\Controller;


use Framework\Contracts\RendererInterface;
use Framework\Contracts\SessionInterface;
use Framework\Controller\AbstractController;
use Framework\Http\Request;
use Framework\Http\Response;
use Framework\Http\Stream;
use QuizApp\Service\CriteriaTrait;
use QuizApp\Service\Paginator;
use QuizApp\Service\QuestionInstanceService;
use QuizApp\Service\QuizInstanceService;

class ResultController extends AbstractController
{
    use CriteriaTrait;

    private const QUIZ_INSTANCE_ID = 'quiz_instance_id';

    /**
     * @var QuizInstanceService
     */
    private $quizInstanceService;
    /**
     * @var int
     */
    private $resultsPerPage;

    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * @var QuestionInstanceService
     */
    private $questionInstanceService;

    /**
     * ResultController constructor.
     * @param RendererInterface $renderer
     * @param QuizInstanceService $quizInstanceService
     * @param QuestionInstanceService $questionInstanceService
     * @param SessionInterface $session
     * @param int $resultsPerPage
     */
    public function __construct
    (
        RendererInterface $renderer,
        QuizInstanceService $quizInstanceService,
        QuestionInstanceService $questionInstanceService,
        SessionInterface $session,
        int $resultsPerPage
    )
    {
        parent::__construct($renderer);
        $this->quizInstanceService = $quizInstanceService;
        $this->questionInstanceService = $questionInstanceService;
        $this->session = $session;
        $this->resultsPerPage = $resultsPerPage;
    }

    /**
     * @param Request $request
     * @param array $requestAttributes
     * @return Response
     */
    public function getResults(Request $request, array $requestAttributes): Response
    {
        $currentPage = $requestAttributes['page'] ?? 1;
        $criteria = $this->getCriteriaFromRequest($requestAttributes, $this->resultsPerPage);
        $results = $this->quizInstanceService->getResultsData($criteria);
        $paginator = new Paginator($this->quizInstanceService->getResultsNumber($criteria), $currentPage, $this->resultsPerPage);

        return $this->renderer->renderView('admin-results-listing.phtml',
            ['results' => $results, 'paginator' => $paginator]);
    }

    /**
     * Gets a specific quiz instance result and its questions and answers.
     *
     * @param Request $request
     * @param array $requestAttributes
     * @return Response
     */
    public function getResult(Request $request, array $requestAttributes): Response
    {
        $quizInstanceId = $requestAttributes[self::QUIZ_INSTANCE_ID];
        $quizInstance = $this->quizInstanceService->findQuiz($quizInstanceId);
        $answeredQuestions = $this->questionInstanceService->getAnsweredQuestions($quizInstanceId);

        return $this->renderer->renderView
        (
            'admin-results.phtml',
            [
                'quizInstance' => $quizInstance,
                'answeredQuestions' => $answeredQuestions,
            ]
        );
    }

    /**
     * Scores a specific quiz instance result.
     *
     * @param Request $request
     * @param array $requestAttributes
     * @return Response
     */
    public function scoreResult(Request $request, array $requestAttributes): Response
    {
        $quizInstanceId = $requestAttributes[self::QUIZ_INSTANCE_ID];
        $score = $request->getParameter('score');
        $this->quizInstanceService->saveScore($quizInstanceId, $score);
        $body = Stream::createFromString('');
        $response = new Response($body, '1.1', 301, '');
        $response = $response->withHeader('Location', '/listResults');

        return $response;
    }
}
