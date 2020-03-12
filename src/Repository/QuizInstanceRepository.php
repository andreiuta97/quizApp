<?php


namespace QuizApp\Repository;


use ReallyOrm\Repository\AbstractRepository;

class QuizInstanceRepository extends AbstractRepository
{
    public function getTableName(): string
    {
        return 'quiz_instance';
    }

    public function getQuestionsNumber(int $quizTemplateId)
    {
        $sql = 'SELECT COUNT(id) FROM question_instance WHERE quiz_instance_id = ?';

        $dbStmt = $this->pdo->prepare($sql);
        $dbStmt->bindValue(1, $quizTemplateId);

        $dbStmt->execute();
        return $dbStmt->fetchColumn();
    }

    public function getResultsData()
    {
        $sql = "select quiz_instance.id as quiz_instance_id, user.name, quiz_template.id as quiz_template_id from quiz_instance 
                    join user on quiz_instance.user_id = user.id 
                    join quiz_template on quiz_instance.quiz_template_id = quiz_template.id";
        $dbStmt = $this->pdo->prepare($sql);
        $dbStmt->execute();
        return $dbStmt->fetchAll();
    }

    /**
     * A correct answer is an answer that differs from its template.
     *
     * @return array
     */
    public function getCorrectAnswerIds() : array
    {
        $sql = "select answer_instance.id from answer_instance 
                    join question_instance on question_instance.id = answer_instance.question_instance_id 
                    join answer_template on answer_template.question_template_id = question_instance.question_template_id 
                    where answer_instance.text != answer_template.text";

        $dbStmt = $this->pdo->prepare($sql);
        $dbStmt->execute();
        return $dbStmt->fetchAll(\PDO::FETCH_COLUMN);
    }
}