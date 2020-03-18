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
use ReallyOrm\Repository\RepositoryManagerInterface;

class QuestionTemplateController extends AbstractController
{
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

    public function getQuestions(Request $request, array $requestAttributes): Response
    {
        $count = $this->questionTemplateService->getQuestionNumber();
        $paginator = new Paginator($count);
        if (isset($requestAttributes['page'])) {
            $paginator->setCurrentPage($requestAttributes['page']);
        }
        $questions = $this->questionTemplateService->getQuestions($paginator->getCurrentPage());

        return $this->renderer->renderView('admin-questions-listing.phtml',
            ['questions' => $questions, 'paginator' => $paginator]);
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
