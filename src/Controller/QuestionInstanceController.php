<?php


namespace QuizApp\Controller;


use Framework\Contracts\RendererInterface;
use Framework\Contracts\SessionInterface;
use Framework\Controller\AbstractController;
use Framework\Http\Request;
use Framework\Http\Response;
use Framework\Http\Stream;
use QuizApp\Repository\AnswerInstanceRepository;
use QuizApp\Repository\QuestionInstanceRepository;
use QuizApp\Service\QuestionInstanceService;
use QuizApp\Service\QuestionTemplateService;
use QuizApp\Service\QuizInstanceService;
use ReallyOrm\Criteria\Criteria;
use ReallyOrm\Entity\AbstractEntity;
use ReallyOrm\Repository\RepositoryManagerInterface;

class QuestionInstanceController extends AbstractController
{
    private const QUIZ_INSTANCE_ID = 'quiz_instance_id';

    /**
     * @var RepositoryManagerInterface
     */
    private $repositoryManager;

    /**
     * @var QuestionInstanceService
     */
    private $questionInstanceService;

    /**
     * @var SessionInterface
     */
    protected $session;

    /**
     * @var QuestionInstanceRepository
     */
    private $questionInstanceRepository;

    /**
     * @var AnswerInstanceRepository
     */
    private $answerInstanceRepository;

    /**
     * @var QuizInstanceService
     */
    private $quizInstanceService;

    public function __construct
    (
        RendererInterface $renderer,
        RepositoryManagerInterface $repositoryManager,
        QuestionInstanceService $questionInstanceService,
        QuestionInstanceRepository $questionInstanceRepository,
        AnswerInstanceRepository $answerInstanceRepository,
        SessionInterface $session,
        QuizInstanceService $quizInstanceService
    ) {
        parent::__construct($renderer);
        $this->repositoryManager = $repositoryManager;
        $this->questionInstanceService = $questionInstanceService;
        $this->session = $session;
        $this->questionInstanceRepository = $questionInstanceRepository;
        $this->answerInstanceRepository = $answerInstanceRepository;
        $this->quizInstanceService = $quizInstanceService;
    }

    /**
     * @param Request $request
     * @param array $requestAttributes
     * @return Response
     */
    public function getQuestionInstance(Request $request, array $requestAttributes)
    {
        $quizInstanceId = $this->session->get(self::QUIZ_INSTANCE_ID);
        $questionInstanceIndex = $requestAttributes['id'];
        $criteria = new Criteria([self::QUIZ_INSTANCE_ID => $quizInstanceId], [], $questionInstanceIndex - 1, 1);
        $firstQuestion = $this->questionInstanceRepository->findBy($criteria)->getFirstItem();
        $answer = $this->answerInstanceRepository->getAnswer($firstQuestion->getId());

        $totalQuestions = $this->questionInstanceService->getQuestionsNumber($quizInstanceId);
        $isLastQuestion = $totalQuestions == $questionInstanceIndex;

        return $this->renderer->renderView('candidate-quiz-page.phtml',
            [
                'question' => $firstQuestion,
                'answer' => $answer,
                'questionInstanceIndex' => $questionInstanceIndex,
                'isLastQuestion' => $isLastQuestion,
            ]
        );
    }
}