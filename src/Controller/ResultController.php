<?php


namespace QuizApp\Controller;


use Framework\Contracts\RendererInterface;
use Framework\Contracts\SessionInterface;
use Framework\Controller\AbstractController;
use Framework\Http\Request;
use Framework\Http\Response;
use QuizApp\Service\QuestionInstanceService;
use QuizApp\Service\QuizInstanceService;

class ResultController extends AbstractController
{
    private const QUIZ_INSTANCE_ID = 'quiz_instance_id';

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
     * Gets all results to be displayed on Results page.
     *
     * @param Request $request
     * @param array $requestAttributes
     * @return Response
     */
    public function getResults(Request $request, array $requestAttributes): Response
    {
        $data = $this->quizInstanceService->getResultsData();

        return $this->renderer->renderView('admin-results-listing.phtml', ['data' => $data]);
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