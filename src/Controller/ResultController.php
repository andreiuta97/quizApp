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
    private $quizInstanceService;
    /**
     * @var SessionInterface
     */
    private $session;
    /**
     * @var QuestionInstanceService
     */
    private $questionInstanceService;

    public function __construct(RendererInterface $renderer, QuizInstanceService $quizInstanceService,QuestionInstanceService $questionInstanceService, SessionInterface $session)
    {
        parent::__construct($renderer);
        $this->quizInstanceService = $quizInstanceService;
        $this->questionInstanceService = $questionInstanceService;
        $this->session = $session;
    }

    public function getResults(Request $request, array $requestAttributes): Response
    {
        $data = $this->quizInstanceService->getResultsData();

        return $this->renderer->renderView('admin-results-listing.phtml', ['data' => $data]);
    }

    public function getResult(Request $request, array $requestAttributes):Response
    {
        $quizInstanceId = $requestAttributes['quiz_instance_id'];
        $quizInstance = $this->quizInstanceService->findQuiz($quizInstanceId);
        $questionsAnswers = $this->questionInstanceService->getAllQuestionsForQuizInstance($quizInstanceId);

        return $this->renderer->renderView
        ('admin-results.phtml',
            [
                'quizInstance' => $quizInstance,
                'questionsAnswers' => $questionsAnswers,
            ]
        );
    }
}