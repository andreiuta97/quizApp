<?php


namespace QuizApp\Repository;


use ReallyOrm\Repository\AbstractRepository;

class AnswerInstanceRepository extends AbstractRepository
{
    public function getTableName(): string
    {
        return 'answer_instance';
    }

    public function getAnswers(int $id)
    {
        $sql='SELECT * FROM answer_instance WHERE question_instance_id = ?';
        $dbStmt=$this->pdo->prepare($sql);
        $dbStmt->bindValue(1, $id);
        $dbStmt->execute();
        $row = $dbStmt->fetch();
        $entity = $this->hydrator->hydrate($this->entityName, $row);
        $this->hydrator->hydrateId($entity, $row['id']);

        return $entity;
    }
}