<?php


namespace QuizApp\Service;


use Framework\Contracts\SessionInterface;
use Framework\Http\Request;
use QuizApp\Entity\AnswerInstance;
use QuizApp\Entity\AnswerTemplate;
use QuizApp\Entity\QuestionInstance;
use QuizApp\Entity\QuestionTemplate;
use QuizApp\Entity\QuizInstance;
use QuizApp\Entity\QuizTemplate;
use QuizApp\Repository\AnswerInstanceRepository;
use QuizApp\Repository\QuestionInstanceRepository;
use ReallyOrm\Criteria\Criteria;
use ReallyOrm\Repository\RepositoryManagerInterface;

class QuestionInstanceService
{
    private const QUESTION_TEMPLATE_ID = 'question_template_id';

    /**
     * @var RepositoryManagerInterface
     */
    private $repositoryManager;

    /**
     * @var QuestionInstanceRepository
     */
    private $questionInstanceRepo;

    /**
     * @var AnswerInstanceRepository
     */
    private $answerInstanceRepo;

    /**
     * @var SessionInterface
     */
    private $session;

    public function __construct
    (
        RepositoryManagerInterface $repositoryManager,
        QuestionInstanceRepository $questionInstanceRepo,
        AnswerInstanceRepository $answerInstanceRepo,
        SessionInterface $session
    )
    {
        $this->repositoryManager = $repositoryManager;
        $this->questionInstanceRepo = $questionInstanceRepo;
        $this->answerInstanceRepo = $answerInstanceRepo;
        $this->session = $session;
    }

    /**
     * Creates all question instances for a quiz instance using the question templates.
     *
     * @param QuizTemplate $quizTemplate
     * @param QuizInstance $quizInstance
     */
    public function createQuestionInstances(QuizTemplate $quizTemplate, QuizInstance $quizInstance)
    {
        $answerTemplateRepo = $this->repositoryManager->getRepository(AnswerTemplate::class);
        $questionTemplateRepo = $this->repositoryManager->getRepository(QuestionTemplate::class);
        $quizTemplateId = $quizTemplate->getId();
        $questionTemplates = $questionTemplateRepo->getQuestionsForQuiz($quizTemplateId);

        /** @var $questionTemplate QuestionTemplate */
        foreach ($questionTemplates as $questionTemplate) {
            $question = new QuestionInstance();
            $question->setText($questionTemplate->getText());
            $question->setType($questionTemplate->getType());
            $question->setQuestionTemplateId($questionTemplate->getId());
            $question->setQuizInstanceId($quizInstance->getId());
            $this->questionInstanceRepo->insertOnDuplicateKeyUpdate($question);

            $answerTemplate = $answerTemplateRepo->findOneBy([self::QUESTION_TEMPLATE_ID => $questionTemplate->getId()]);
            $answer = new AnswerInstance();
            $answer->setText($answerTemplate->getText());
            $answer->setQuestionInstanceId($question->getId());
            $this->answerInstanceRepo->insertOnDuplicateKeyUpdate($answer);
        }
    }

    /**
     * Retrieves all questions and their answers for a particular quiz instance.
     *
     * @param int $quizInstanceId
     * @return array
     */
    public function getAnsweredQuestions(int $quizInstanceId): array
    {
        $questions = $this->questionInstanceRepo->getQuestions($quizInstanceId);
        $answeredQuestions = [];
        foreach ($questions as $question) {
            $answer = $this->answerInstanceRepo->getAnswer($question->getId());

            $answeredQuestion = new AnsweredQuestion();
            $answeredQuestion->setQuestion($question);
            $answeredQuestion->setAnswer($answer);
            $answeredQuestions[] = $answeredQuestion;
        }

        return $answeredQuestions;
    }

    /**
     * Counts the number of questions from a particular quiz instance.
     *
     * @param int $quizInstanceId
     * @return int
     */
    public function getQuestionsNumber(int $quizInstanceId): int
    {
        return $this->questionInstanceRepo->getQuestionsNumber($quizInstanceId);
    }
}