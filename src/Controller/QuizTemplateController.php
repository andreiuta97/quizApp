<?php


namespace QuizApp\Controller;


use Framework\Contracts\RendererInterface;
use Framework\Controller\AbstractController;
use Framework\Http\Request;
use Framework\Http\Response;
use Framework\Http\Stream;
use QuizApp\Entity\QuizTemplate;
use QuizApp\Service\QuizTemplateService;
use ReallyOrm\Criteria\Criteria;
use ReallyOrm\Repository\RepositoryManagerInterface;

class QuizTemplateController extends AbstractController
{
    /**
     * @var RepositoryManagerInterface
     */
    private $repositoryManager;
    /**
     * @var QuizTemplateService
     */
    private $quizTemplateService;

    public function __construct
    (
        RendererInterface $renderer,
        RepositoryManagerInterface $repositoryManager,
        QuizTemplateService $quizTemplateService
    )
    {
        parent::__construct($renderer);
        $this->repositoryManager = $repositoryManager;
        $this->quizTemplateService = $quizTemplateService;
    }

    public function addQuiz(Request $request, array $requestAttributes): Response
    {
        $info = $request->getParameters();
        $this->quizTemplateService->add($info);
        $body = Stream::createFromString('');
        $response = new Response($body, '1.1', 301, '');
        $response = $response->withHeader('Location', 'http://local.quiz.com/listQuizzes');

        return $response;
    }

    public function getQuiz(Request $request, array $requestAttributes): Response
    {
        $id = $requestAttributes['id'];
        $this->quizTemplateService->getQuiz($id);
        $body = Stream::createFromString('');

        return new Response($body, '1.1', 200, '');
    }

    public function updateQuiz(Request $request, array $requestAttributes): Response
    {
        $id = $requestAttributes['id'];
        $info = $request->getParameters();
        $this->quizTemplateService->update($id, $info);
        $body = Stream::createFromString('');
        $response = new Response($body, '1.1', 301, '');
        $response = $response->withHeader('Location', 'http://local.quiz.com/listQuizzes');

        return $response;
    }

    public function deleteQuiz(Request $request, array $requestAttributes): Response
    {
        $id = $requestAttributes['id'];
        $this->quizTemplateService->delete($id);
        $body = Stream::createFromString('');
        $response = new Response($body, '1.1', 301, '');
        $response = $response->withHeader('Location', 'http://local.quiz.com/listQuizzes');

        return $response;
    }

    public function getQuizzes(Request $request, array $requestAttributes): Response
    {
        $quizRepo = $this->repositoryManager->getRepository(QuizTemplate::class);
        // get filters and pagination from request
        $criteria = new Criteria();
        $quizzes = $quizRepo->findBy($criteria);
        $quizzes = ['quizzes' => $quizzes];
        return $this->renderer->renderView('admin-quizzes-listing.phtml', $quizzes);
    }

    public function addNewQuiz(Request $request, array $requestAttributes): Response
    {
        return $this->renderer->renderView('admin-quiz-add.phtml', $requestAttributes);
    }

    public function editQuiz(Request $request, array $requestAttributes): Response
    {
        $id = $requestAttributes['id'];
        $quiz = $this->quizTemplateService->getQuiz($id);

        return $this->renderer->renderView('admin-quiz-edit.phtml', ['quiz' => $quiz]);
    }
}