<?php


namespace QuizApp\Controller;


use Framework\Contracts\RendererInterface;
use Framework\Controller\AbstractController;
use Framework\Http\Request;
use Framework\Http\Response;
use Framework\Http\Stream;
use QuizApp\Entity\AnswerTemplate;
use QuizApp\Service\CriteriaTrait;
use QuizApp\Service\Paginator;
use QuizApp\Service\QuestionTemplateService;
use ReallyOrm\Criteria\Criteria;
use ReallyOrm\Repository\RepositoryManagerInterface;

class QuestionTemplateController extends AbstractController
{
    use CriteriaTrait;

    /**
     * @var RepositoryManagerInterface
     */
    private $repositoryManager;

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
        QuestionTemplateService $questionTemplateService,
        int $resultsPerPage
    )
    {
        parent::__construct($renderer);
        $this->repositoryManager = $repositoryManager;
        $this->questionTemplateService = $questionTemplateService;
        $this->resultsPerPage = $resultsPerPage;
    }

    /**
     * Adds a question to the database and redirects to "Questions Listing" page.
     * @param Request $request
     * @param array $requestAttributes
     * @return Response
     */
    public function addQuestion(Request $request, array $requestAttributes): Response
    {
        $info = $request->getParameters();
        $this->questionTemplateService->add($info);

        return $this->createRedirectResponse('/listQuestions');
    }

    /**
     * Updates the selected question from the database and redirects to "Questions Listing" page.
     *
     * @param Request $request
     * @param array $requestAttributes
     * @return Response
     */
    public function updateQuestion(Request $request, array $requestAttributes): Response
    {
        $id = $requestAttributes['id'];
        $info = $request->getParameters();
        $this->questionTemplateService->update($id, $info);

        return $this->createRedirectResponse('/listQuestions');
    }

    /**
     * Deletes the selected user from database and redirects to "Questions Listing" page.
     *
     * @param Request $request
     * @param array $requestAttributes
     * @return Response
     */
    public function deleteQuestion(Request $request, array $requestAttributes): Response
    {
        $id = $requestAttributes['id'];
        $this->questionTemplateService->delete($id);

        return $this->createRedirectResponse('/listQuestions');
    }

    /**
     * Displays all questions from database in a paginated, filtered and sorted manner.
     *
     * @param Request $request
     * @param array $requestAttributes
     * @return Response
     */
    public function getQuestions(Request $request, array $requestAttributes): Response
    {
        $currentPage = $requestAttributes['page'] ?? 1;
        $criteria = $this->getCriteriaFromRequest($requestAttributes, $this->resultsPerPage);
        $questionSearchResult = $this->questionTemplateService->getQuestions($criteria);
        $paginator = new Paginator($questionSearchResult->getCount(), $currentPage, $this->resultsPerPage);

        return $this->renderer->renderView('admin-questions-listing.phtml',
            ['questions' => $questionSearchResult->getItems(), 'paginator' => $paginator, 'order' => $requestAttributes['order']]);
    }

    /**
     * Displays the "Add Question" page.
     *
     * @param Request $request
     * @param array $requestAttributes
     * @return Response
     */
    public function addNewQuestion(Request $request, array $requestAttributes): Response
    {
        return $this->renderer->renderView('admin-question-add.phtml', $requestAttributes);
    }

    /**
     * Displays the "Edit Question" page containing the form pre-filled with the selected question's information.
     *
     * @param Request $request
     * @param array $requestAttributes
     * @return Response
     */
    public function editQuestion(Request $request, array $requestAttributes): Response
    {
        $id = $requestAttributes['id'];
        $question = $this->questionTemplateService->getQuestion($id);
        $answerRepo = $this->repositoryManager->getRepository(AnswerTemplate::class);
        $answer = $answerRepo->findOneBy(['question_template_id' => $question->getId()]);
        $questionAnswer = $answerRepo->getAnswerForQuestion($id);


        return $this->renderer->renderView('admin-question-edit.phtml', ['question' => $question, 'answer' => $answer, 'questionAnswer' => $questionAnswer]);
    }
}
