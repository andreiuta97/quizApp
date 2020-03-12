<?php


namespace QuizApp\Repository;

use ReallyOrm\Repository\AbstractRepository;

class QuestionTemplateRepository extends AbstractRepository
{
    public function getTableName(): string
    {
        return 'question_template';
    }

    public function getAnswerForQuestion(int $id)
    {
        $sql = 'SELECT * FROM answer_template WHERE question_template_id = ?';
        $dbStmt = $this->pdo->prepare($sql);
        $dbStmt->bindValue(1, $id);
        $dbStmt->execute();

        return $dbStmt->fetch();
    }
}