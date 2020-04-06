<?php


namespace QuizApp\Controller;


use Framework\Contracts\RendererInterface;
use Framework\Contracts\SessionInterface;
use Framework\Controller\AbstractController;
use Framework\Http\Request;
use Framework\Http\Response;
use QuizApp\Service\Paginator;
use QuizApp\Service\QuestionInstanceService;
use QuizApp\Service\QuizInstanceService;
use ReallyOrm\Criteria\Criteria;

class ResultController extends AbstractController
{
    private const QUIZ_INSTANCE_ID = 'quiz_instance_id';

    const RESULTS_PER_PAGE = 5;

    /**
     * @var QuizInstanceService
     */
    private $quizInstanceService;

    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * @var QuestionInstanceService
     */
    private $questionInstanceService;

    public function __construct
    (
        RendererInterface $renderer,
        QuizInstanceService $quizInstanceService,
        QuestionInstanceService $questionInstanceService,
        SessionInterface $session
    ) {
        parent::__construct($renderer);
        $this->quizInstanceService = $quizInstanceService;
        $this->questionInstanceService = $questionInstanceService;
        $this->session = $session;
    }

    /**
     * Creates a new Criteria entity necessary for displaying the results.
     *
     * @param array $requestAttributes
     * @return Criteria
     */
    private function getCriteriaFromRequest(array $requestAttributes): Criteria
    {
        $currentPage = $requestAttributes['page'] ?? 1;
        $from = ($currentPage - 1) * self::RESULTS_PER_PAGE;

        return new Criteria([], [], $from, self::RESULTS_PER_PAGE);
    }

    /**
     * @param Request $request
     * @param array $requestAttributes
     * @return Response
     */
    public function getResults(Request $request, array $requestAttributes): Response
    {
        $currentPage = $requestAttributes['page'] ?? 1;
        $criteria = $this->getCriteriaFromRequest($requestAttributes);
        $data = $this->quizInstanceService->getResultsData($criteria);
        $paginator = new Paginator($data->getCount(), $currentPage, self::RESULTS_PER_PAGE);

        return $this->renderer->renderView('admin-results-listing.phtml',
            ['data' => $data->getItems(), 'paginator' => $paginator]);
    }

    /**
     * Gets a specific quiz instance results and its questions and answers.
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
}
