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
    )
    {
        parent::__construct($renderer);
        $this->repositoryManager = $repositoryManager;
        $this->questionInstanceService = $questionInstanceService;
        $this->session = $session;
        $this->questionInstanceRepository = $questionInstanceRepository;
        $this->answerInstanceRepository = $answerInstanceRepository;
        $this->quizInstanceService = $quizInstanceService;
    }

    public function getQuestionInstance(Request $request, array $requestAttributes)
    {
        $quizInstanceId = $this->session->get('quizInstanceId');
        $questionInstanceIndex = $requestAttributes['id'];
        $criteria = new Criteria(['quiz_instance_id' => $quizInstanceId], [], $questionInstanceIndex - 1, 1);
        $question = $this->questionInstanceRepository->findBy($criteria);
        $answer = $this->answerInstanceRepository->getAnswers($question[0]->getId());

        $totalQuestions = $this->quizInstanceService->getQuestionsNumber($quizInstanceId);
        $isLastQuestion = $totalQuestions == $questionInstanceIndex;

        return $this->renderer->renderView('candidate-quiz-page.phtml', ['question' => $question[0], 'answer' => $answer, 'questionInstanceIndex' => $questionInstanceIndex, 'isLastQuestion' => $isLastQuestion]);
    }
}