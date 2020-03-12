<?php


namespace QuizApp\Controller;


use Framework\Contracts\RendererInterface;
use Framework\Controller\AbstractController;
use Framework\Http\Request;
use Framework\Http\Response;
use QuizApp\Entity\QuizTemplate;
use QuizApp\Service\QuizInstanceService;
use ReallyOrm\Criteria\Criteria;
use ReallyOrm\Repository\RepositoryManagerInterface;

class QuizInstanceController extends AbstractController
{
    /**
     * @var RepositoryManagerInterface
     */
    private $repositoryManager;
    /**
     * @var QuizInstanceService
     */
    private $quizInstanceService;

    public function __construct
    (
        RendererInterface $renderer,
        RepositoryManagerInterface $repositoryManager,
        QuizInstanceService $quizInstanceService
    )
    {
        parent::__construct($renderer);
        $this->repositoryManager = $repositoryManager;
        $this->quizInstanceService = $quizInstanceService;
    }

    public function getQuizzes(Request $request, array $requestAttributes): Response
    {
        $quizRepo = $this->repositoryManager->getRepository(QuizTemplate::class);
        // get filters and pagination from request
        $criteria = new Criteria();
        $quizzes = $quizRepo->findBy($criteria);
        return $this->renderer->renderView('candidate-quiz-listing.phtml', ['quizzes' => $quizzes]);
    }
}