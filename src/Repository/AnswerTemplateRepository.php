<?php


namespace QuizApp\Repository;


use ReallyOrm\Repository\AbstractRepository;

class AnswerTemplateRepository extends AbstractRepository
{
    public function getTableName(): string
    {
        return 'answer_template';
    }

    public function getAnswerForQuestion(int $questionId)
    {
        $sql = 'SELECT * FROM answer_template WHERE question_template_id = ?';
        $dbStmt = $this->pdo->prepare($sql);
        $dbStmt->bindValue(1, $questionId);
        $dbStmt->execute();
        $row = $dbStmt->fetch();
        $answer = $this->hydrator->hydrate($this->entityName, $row);
        $this->hydrator->hydrateId($answer, $row['id']);

        return $answer;
    }
}