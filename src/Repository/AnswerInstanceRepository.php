<?php


namespace QuizApp\Repository;


use QuizApp\Entity\AnswerInstance;
use ReallyOrm\Repository\AbstractRepository;

class AnswerInstanceRepository extends AbstractRepository
{
    public function getAnswers(int $id): AnswerInstance
    {
        $sql = 'SELECT * FROM ' . $this->getTableName() . ' WHERE question_instance_id = ?';
        $dbStmt = $this->pdo->prepare($sql);
        $dbStmt->bindValue(1, $id);
        $dbStmt->execute();
        $row = $dbStmt->fetch();
        /** @var AnswerInstance $entity */
        $entity = $this->hydrator->hydrate($this->entityName, $row);
        $this->hydrator->hydrateId($entity, $row['id']);

        return $entity;
    }
}