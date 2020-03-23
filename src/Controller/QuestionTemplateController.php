<?php


namespace QuizApp\Controller;


use Framework\Contracts\RendererInterface;
use Framework\Controller\AbstractController;
use Framework\Http\Request;
use Framework\Http\Response;
use Framework\Http\Stream;
use QuizApp\Entity\AnswerTemplate;
use QuizApp\Service\Paginator;
use QuizApp\Service\QuestionTemplateService;
use ReallyOrm\Criteria\Criteria;
use ReallyOrm\Repository\RepositoryManagerInterface;

class QuestionTemplateController extends AbstractController
{
    const RESULTS_PER_PAGE = 5;
    /**
     * @var RepositoryManagerInterface
     */
    private $repositoryManager;
    /**
     * @var QuestionTemplateService
     */
    private $questionTemplateService;

    public function __construct
    (
        RendererInterface $renderer,
        RepositoryManagerInterface $repositoryManager,
        QuestionTemplateService $questionTemplateService
    )
    {
        parent::__construct($renderer);
        $this->repositoryManager = $repositoryManager;
        $this->questionTemplateService = $questionTemplateService;
    }

    public function addQuestion(Request $request, array $requestAttributes): Response
    {
        $info = $request->getParameters();
        $this->questionTemplateService->add($info);
        $body = Stream::createFromString('');
        $response = new Response($body, '1.1', 301, '');
        $response = $response->withHeader('Location', 'http://local.quiz.com/listQuestions');

        return $response;
    }

    public function getQuestion(Request $request, array $requestAttributes): Response
    {
        $id = $requestAttributes['id'];
        $this->questionTemplateService->getQuestion($id);
        $body = Stream::createFromString('');

        return new Response($body, '1.1', 200, '');
    }

    public function updateQuestion(Request $request, array $requestAttributes): Response
    {
        $id = $requestAttributes['id'];
        $info = $request->getParameters();
        $this->questionTemplateService->update($id, $info);
        $body = Stream::createFromString('');
        $response = new Response($body, '1.1', 301, '');
        $response = $response->withHeader('Location', 'http://local.quiz.com/listQuestions');

        return $response;
    }

    public function deleteQuestion(Request $request, array $requestAttributes): Response
    {
        $id = $requestAttributes['id'];
        $this->questionTemplateService->delete($id);
        $body = Stream::createFromString('');
        $response = new Response($body, '1.1', 301, '');
        $response = $response->withHeader('Location', 'http://local.quiz.com/listQuestions');

        return $response;
    }

    private function getCriteriaFromRequest(array $requestAttributes): Criteria
    {
        $filters = isset($requestAttributes['text']) ? ['text' => $requestAttributes['text']] : [];
        $currentPage = isset($requestAttributes['page']) ? $requestAttributes['page'] : 1;
        $from = ($currentPage - 1) * self::RESULTS_PER_PAGE;

        return new Criteria($filters, [], $from, self::RESULTS_PER_PAGE);
    }

    public function getQuestions(Request $request, array $requestAttributes): Response
    {
        $currentPage = isset($requestAttributes['page']) ? $requestAttributes['page'] : 1;
        $criteria = $this->getCriteriaFromRequest($requestAttributes);
        $questionSearchResult = $this->questionTemplateService->getQuestions($criteria);
        $paginator = new Paginator($questionSearchResult->getCount(), $currentPage, self::RESULTS_PER_PAGE);

        return $this->renderer->renderView('admin-questions-listing.phtml',
            ['questions' => $questionSearchResult->getItems(), 'paginator' => $paginator]);
    }

    public function addNewQuestion(Request $request, array $requestAttributes): Response
    {
        return $this->renderer->renderView('admin-question-add.phtml', $requestAttributes);
    }

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
