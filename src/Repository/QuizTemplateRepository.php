<?php


namespace QuizApp\Repository;

use ReallyOrm\Repository\AbstractRepository;

class QuizTemplateRepository extends AbstractRepository
{
    public function getTableName(): string
    {
        return 'quiz_template';
    }

    public function saveQuestionsForQuiz($id, $questions)
    {
        $sql = 'DELETE FROM quiz_question_template WHERE quiz_template_id = ?';
        $dbStmt = $this->pdo->prepare($sql);
        $dbStmt->bindValue(1, $id);
        $dbStmt->execute();

        foreach ($questions as $questionId){
            $query = 'INSERT INTO quiz_question_template (quiz_template_id, question_template_id) VALUES (:quizId, :questionId)';
            $sqlStm = $this->pdo->prepare($query);
            $sqlStm->bindParam(':quizId', $id);
            $sqlStm->bindParam('questionId', $questionId);
            $sqlStm->execute();
        }
    }

    public function getQuestionsForQuiz(int $id)
    {
        $sql = 'SELECT * FROM question_template WHERE id IN (SELECT question_template_id FROM quiz_question_template WHERE quiz_template_id = ?)';

        $dbStmt = $this->pdo->prepare($sql);
        $dbStmt->bindValue(1, $id);

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