<?php


namespace QuizApp\Controller;


use Framework\Contracts\RendererInterface;
use Framework\Controller\AbstractController;
use Framework\Http\Request;
use Framework\Http\Response;
use Framework\Http\Stream;
use QuizApp\Entity\QuestionTemplate;
use QuizApp\Entity\QuizTemplate;
use QuizApp\Service\CriteriaTrait;
use QuizApp\Service\Paginator;
use QuizApp\Service\QuestionTemplateService;
use QuizApp\Service\QuizTemplateService;
use ReallyOrm\Criteria\Criteria;
use ReallyOrm\Repository\RepositoryManagerInterface;

class QuizTemplateController extends AbstractController
{
    use CriteriaTrait;

    /**
     * @var RepositoryManagerInterface
     */
    private $repositoryManager;

    /**
     * @var QuizTemplateService
     */
    private $quizTemplateService;

    /**
     * @var QuestionTemplateService
     */
    private $questionTemplateService;
    /**
     * @var int
     */
    private $resultsPerPage;

    public function __construct
    (
        RendererInterface $renderer,
        RepositoryManagerInterface $repositoryManager,
        QuizTemplateService $quizTemplateService,
        QuestionTemplateService $questionTemplateService,
        int $resultsPerPage
    ) {
        parent::__construct($renderer);
        $this->repositoryManager = $repositoryManager;
        $this->quizTemplateService = $quizTemplateService;
        $this->questionTemplateService = $questionTemplateService;
        $this->resultsPerPage = $resultsPerPage;
    }

    public function addQuiz(Request $request, array $requestAttributes): Response
    {
        $info = $request->getParameters();
        $this->quizTemplateService->add($info);

        return $this->createRedirectResponse('/listQuizzes');
    }

    public function updateQuiz(Request $request, array $requestAttributes): Response
    {
        $id = $requestAttributes['id'];
        $info = $request->getParameters();
        $this->quizTemplateService->update($id, $info);

        return $this->createRedirectResponse('/listQuizzes');
    }

    public function deleteQuiz(Request $request, array $requestAttributes): Response
    {
        $id = $requestAttributes['id'];
        $this->quizTemplateService->delete($id);

        return $this->createRedirectResponse('/listQuizzes');
    }

    public function getQuizzes(Request $request, array $requestAttributes): Response
    {
        $currentPage = $requestAttributes['page'] ?? 1;
        $criteria = $this->getCriteriaFromRequest($requestAttributes, $this->resultsPerPage);
        $quizzesSearchResult = $this->quizTemplateService->getQuizzes($criteria);
        $paginator = new Paginator($quizzesSearchResult->getCount(), $currentPage, $this->resultsPerPage);

        return $this->renderer->renderView('admin-quizzes-listing.phtml',
            ['quizzes' => $quizzesSearchResult->getItems(), 'paginator' => $paginator]);
    }

    public function addNewQuiz(Request $request, array $requestAttributes): Response
    {
        $questionRepo = $this->repositoryManager->getRepository(QuestionTemplate::class);
        $criteria = new Criteria();
        $questions = $questionRepo->findBy($criteria);

        return $this->renderer->renderView('admin-quiz-add.phtml', ['questions' => $questions]);
    }

    public function editQuiz(Request $request, array $requestAttributes): Response
    {
        $id = $requestAttributes['id'];
        /**
         * @var $quiz QuizTemplate
         */
        $quiz = $this->quizTemplateService->getQuiz($id);
        $questionRepo = $this->repositoryManager->getRepository(QuestionTemplate::class);
        $criteria = new Criteria();
        $questions = $questionRepo->findBy($criteria);
        $quizQuestions = $questionRepo->getQuestionIdsForQuiz($quiz->getId());

        return $this->renderer->renderView('admin-quiz-edit.phtml', ['quiz' => $quiz, 'questions' => $questions, 'quizQuestions' => $quizQuestions]);
    }
}