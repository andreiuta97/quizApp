<?php


namespace QuizApp\Controller;


use Framework\Contracts\RendererInterface;
use Framework\Controller\AbstractController;
use Framework\Http\Request;
use Framework\Http\Response;
use Framework\Http\Stream;
use QuizApp\Entity\QuestionTemplate;
use QuizApp\Entity\QuizTemplate;
use QuizApp\Service\Paginator;
use QuizApp\Service\QuestionTemplateService;
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
    private $questionTemplateService;

    public function __construct
    (
        RendererInterface $renderer,
        RepositoryManagerInterface $repositoryManager,
        QuizTemplateService $quizTemplateService,
        QuestionTemplateService $questionTemplateService
    )
    {
        parent::__construct($renderer);
        $this->repositoryManager = $repositoryManager;
        $this->quizTemplateService = $quizTemplateService;
        $this->questionTemplateService = $questionTemplateService;
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
        $filters = [];
        if (isset($requestAttributes['name'])) {
            $filters['name'] = $requestAttributes['name'];
        }
        $count = $this->quizTemplateService->getQuizzesNumber($filters);
        $paginator = new Paginator($count);
        if (isset($requestAttributes['page'])) {
            $paginator->setCurrentPage($requestAttributes['page']);
        }
        $quizzes = $this->quizTemplateService->getQuizzes($filters, $paginator->getCurrentPage());

        return $this->renderer->renderView('admin-quizzes-listing.phtml',
            ['quizzes' => $quizzes, 'paginator' => $paginator]);
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