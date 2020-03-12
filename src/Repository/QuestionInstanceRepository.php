<?php


namespace QuizApp\Repository;


use ReallyOrm\Repository\AbstractRepository;

class QuestionInstanceRepository extends AbstractRepository
{
    public function getTableName(): string
    {
        return 'question_instance';
    }

    public function getQuestions(int $id)
    {
        $sql='SELECT * FROM question_instance WHERE quiz_instance_id = ?';
        $dbStmt=$this->pdo->prepare($sql);
        $dbStmt->bindValue(1, $id);
        $row = $dbStmt->fetch();
        $entity = $this->hydrator->hydrate($this->entityName, $row);
        $this->hydrator->hydrateId($entity, $row['id']);

        return $entity;
    }
}