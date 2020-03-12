<?php


namespace QuizApp\Service;


use Framework\Contracts\SessionInterface;
use QuizApp\Entity\AnswerTemplate;
use QuizApp\Entity\QuestionTemplate;
use QuizApp\Repository\AnswerTemplateRepository;
use QuizApp\Repository\QuestionTemplateRepository;
use ReallyOrm\Criteria\Criteria;

class QuestionTemplateService
{
    private $questionTemplateRepo;
    private $answerTemplateRepo;
    private $session;

    public function __construct
    (
        QuestionTemplateRepository $questionTemplateRepo,
        AnswerTemplateRepository $answerTemplateRepo,
        SessionInterface $session
    )
    {
        $this->questionTemplateRepo = $questionTemplateRepo;
        $this->answerTemplateRepo = $answerTemplateRepo;
        $this->session = $session;
    }

    public function add(array $info)
    {
        $question = new QuestionTemplate();
        $question->setText($info['text']);
        $question->setType($info['type']);

        $this->questionTemplateRepo->insertOnDuplicateKeyUpdate($question);

        $answer = new AnswerTemplate();
        $answer->setText($info['answer']);
        $answer->setQuestionId($question->getId());

        $this->answerTemplateRepo->insertOnDuplicateKeyUpdate($answer);
    }

    public function getQuestion(int $id)
    {
        return $this->questionTemplateRepo->find($id);
    }

    public function update(int $id, array $info)
    {
        $question = $this->getQuestion($id);
        $question->setText($info['text']);
        $question->setType($info['type']);

        $this->questionTemplateRepo->insertOnDuplicateKeyUpdate($question);

        $answer = new AnswerTemplate();
        $answer = $this->answerTemplateRepo->findOneBy(['question_template_id' => $question->getId()]);
        $answer->setText($info['answer']);
        $answer->setQuestionId($question->getId());

        $this->answerTemplateRepo->insertOnDuplicateKeyUpdate($answer);
    }

    public function delete(int $id)
    {
        $question = $this->getQuestion($id);
        $this->questionTemplateRepo->delete($question);
    }
}