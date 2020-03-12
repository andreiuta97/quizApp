<?php


namespace QuizApp\Service;


use Framework\Contracts\SessionInterface;
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

    public function createQuestionInstances(QuizTemplate $quizTemplate, QuizInstance $quizInstance)
    {
        $answerTemplateRepo = $this->repositoryManager->getRepository(AnswerTemplate::class);
        $questionTemplateRepo = $this->repositoryManager->getRepository(QuestionTemplate::class);

        //$quizTemplateRepo = $this->repositoryManager->getRepository(QuizTemplate::class);
        $quizTemplateId = $quizTemplate->getId();
        $questionTemplates = $questionTemplateRepo->getQuestionsForQuiz($quizTemplateId);

        foreach ($questionTemplates as $questionTemplate) {
            $question = new QuestionInstance();
            $question->setText($questionTemplate->getText());
            $question->setType($questionTemplate->getType());
            $question->setQuestionTemplateId($questionTemplate->getId());
            $question->setQuizInstanceId($quizInstance->getId());
            $this->questionInstanceRepo->insertOnDuplicateKeyUpdate($question);

            $answerTemplate = $answerTemplateRepo->findOneBy(['question_template_id' => $questionTemplate->getId()]);
            $answer = new AnswerInstance();
            $answer->setText($answerTemplate->getText());
            $answer->setQuestionInstanceId($question->getId());
            $this->answerInstanceRepo->insertOnDuplicateKeyUpdate($answer);
        }
    }

    public function getQuestionInstance(int $id)
    {
        return $this->questionInstanceRepo->find($id);
    }
}