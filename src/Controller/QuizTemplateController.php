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

    /**
     * Adds a quiz to database and redirects to "Quizzes Listing" page.
     *
     * @param Request $request
     * @param array $requestAttributes
     * @return Response
     */
    public function addQuiz(Request $request, array $requestAttributes): Response
    {
        $info = $request->getParameters();
        $this->quizTemplateService->add($info);
        $body = Stream::createFromString('');
        $response = new Response($body, '1.1', 301, '');
        $response = $response->withHeader('Location', '/listQuizzes');


        return $response;
    }

    public function getQuiz(Request $request, array $requestAttributes): Response
    {
        $id = $requestAttributes['id'];
        $this->quizTemplateService->getQuiz($id);
        $body = Stream::createFromString('');

        return new Response($body, '1.1', 200, '');
    }

    /**
     * Updates the selected quiz from the database and redirects to "Quizzes Listing" page.
     *
     * @param Request $request
     * @param array $requestAttributes
     * @return Response
     */
    public function updateQuiz(Request $request, array $requestAttributes): Response
    {
        $id = $requestAttributes['id'];
        $info = $request->getParameters();
        $this->quizTemplateService->update($id, $info);
        $body = Stream::createFromString('');
        $response = new Response($body, '1.1', 301, '');
        $response = $response->withHeader('Location', '/listQuizzes');

        return $response;
    }

    /**
     * Deletes the selected quiz from the database and redirects to "Quizzes Listing" page.
     *
     * @param Request $request
     * @param array $requestAttributes
     * @return Response
     */
    public function deleteQuiz(Request $request, array $requestAttributes): Response
    {
        $id = $requestAttributes['id'];
        $this->quizTemplateService->delete($id);
        $body = Stream::createFromString('');
        $response = new Response($body, '1.1', 301, '');
        $response = $response->withHeader('Location', '/listQuizzes');

        return $response;
    }

    /**
     * Displays all quizzes from database in a paginated, filtered and sorted manner.
     *
     * @param Request $request
     * @param array $requestAttributes
     * @return Response
     */
    public function getQuizzes(Request $request, array $requestAttributes): Response
    {
        $currentPage = $requestAttributes['page'] ?? 1;
        $criteria = $this->getCriteriaFromRequest($requestAttributes, $this->resultsPerPage);
        $quizzesSearchResult = $this->quizTemplateService->getQuizzes($criteria);
        $paginator = new Paginator($quizzesSearchResult->getCount(), $currentPage, $this->resultsPerPage);

        return $this->renderer->renderView('admin-quizzes-listing.phtml',
            ['quizzes' => $quizzesSearchResult->getItems(), 'paginator' => $paginator, 'order' => $requestAttributes['order']]);
    }

    /**
     * Displays the "Add Quiz" page.
     * All available questions will be displayed in the form.
     *
     * @param Request $request
     * @param array $requestAttributes
     * @return Response
     */
    public function addNewQuiz(Request $request, array $requestAttributes): Response
    {
        $questionRepo = $this->repositoryManager->getRepository(QuestionTemplate::class);
        $criteria = new Criteria();
        $questions = $questionRepo->findBy($criteria);

        return $this->renderer->renderView('admin-quiz-add.phtml', ['questions' => $questions]);
    }

    /**
     * Displays the "Edit Quiz page containing the form pre-filled with the selected quiz's information.
     * All available questions will be displayed in the form.
     *
     * @param Request $request
     * @param array $requestAttributes
     * @return Response
     */
    public function editQuiz(Request $request, array $requestAttributes): Response
    {
        $id = $requestAttributes['id'];
        /** @var $quiz QuizTemplate */
        $quiz = $this->quizTemplateService->getQuiz($id);
        $questionRepo = $this->repositoryManager->getRepository(QuestionTemplate::class);
        $criteria = new Criteria();
        $questions = $questionRepo->findBy($criteria);
        $quizQuestions = $questionRepo->getQuestionIdsForQuiz($quiz->getId());

        return $this->renderer->renderView('admin-quiz-edit.phtml', ['quiz' => $quiz, 'questions' => $questions, 'quizQuestions' => $quizQuestions]);
    }
}