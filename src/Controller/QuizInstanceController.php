<?php


namespace QuizApp\Controller;


use Framework\Contracts\RendererInterface;
use Framework\Controller\AbstractController;
use Framework\Http\Request;
use Framework\Http\Response;
use Framework\Http\Stream;
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

    public function __construct
    (
        RendererInterface $renderer,
        RepositoryManagerInterface $repositoryManager,
        QuizTemplateRepository $quizTemplateRepository,
        QuizInstanceService $quizInstanceService,
        QuestionInstanceService $questionInstanceService
    )
    {
        parent::__construct($renderer);
        $this->repositoryManager = $repositoryManager;
        $this->quizInstanceService = $quizInstanceService;
        $this->questionInstanceService = $questionInstanceService;
        $this->quizTemplateRepository = $quizTemplateRepository;
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

    private function getCriteriaFromRequest(array $requestAttributes): Criteria
    {
        $currentPage = $requestAttributes['page'] ?? 1;
        $from = ($currentPage - 1) * self::RESULTS_PER_PAGE;

        return new Criteria([], [], $from, self::RESULTS_PER_PAGE);
    }

    public function getQuizzes(Request $request, array $requestAttributes): Response
    {
        $currentPage = $requestAttributes['page'] ?? 1;
        $criteria=$this->getCriteriaFromRequest($requestAttributes);
        $quizzesSearchResult=$this->quizInstanceService->getQuizzes($criteria);
        $paginator = new Paginator($quizzesSearchResult->getCount(),$currentPage,self::RESULTS_PER_PAGE);

        return $this->renderer->renderView('candidate-quiz-listing.phtml',
            ['quizzes' => $quizzesSearchResult->getItems(), 'paginator' => $paginator]);
    }

    public function getQuizStarted(Request $request, array $requestAttributes): Response
    {
        return $this->renderer->renderView('candidate-quiz-page.phtml', $requestAttributes);
    }

    public function showSuccess(Request $request, array $requestAttributes): Response
    {
        return $this->renderer->renderView('quiz-success-page.phtml', $requestAttributes);
    }
}