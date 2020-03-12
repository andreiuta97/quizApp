<?php


namespace QuizApp\Repository;

use ReallyOrm\Repository\AbstractRepository;

class QuestionTemplateRepository extends AbstractRepository
{
    public function getTableName(): string
    {
        return 'question_template';
    }

    public function getQuestionsForQuiz(int $id)
    {
        $sql = 'SELECT * FROM question_template WHERE id IN (SELECT question_template_id FROM quiz_question_template WHERE quiz_template_id = ?)';

        $dbStmt = $this->pdo->prepare($sql);
        $dbStmt->bindValue(1, $id, \PDO::PARAM_INT);

        $dbStmt->execute();
        $array = $dbStmt->fetchAll();
        $objects = [];
        foreach ($array as $row) {
            $object = $this->hydrator->hydrate($this->entityName, $row);
            $this->hydrator->hydrateId($object, $row['id']);
            $objects[] = $object;
        }

        return $objects;
    }

    public function getQuestionIdsForQuiz(int $id)
    {
        $sql = 'SELECT id FROM question_template WHERE id IN (SELECT question_template_id FROM quiz_question_template WHERE quiz_template_id = ?)';

        $dbStmt = $this->pdo->prepare($sql);
        $dbStmt->bindValue(1, $id);

        $dbStmt->execute();
        return $dbStmt->fetchAll(\PDO::FETCH_COLUMN);
    }

}