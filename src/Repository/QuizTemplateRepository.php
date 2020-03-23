<?php


namespace QuizApp\Repository;

use ReallyOrm\Criteria\Criteria;
use ReallyOrm\Repository\AbstractRepository;

class QuizTemplateRepository extends AbstractRepository
{
    public function saveQuestionsForQuiz($id, $questions)
    {
        $sql = 'DELETE FROM quiz_question_template WHERE quiz_template_id = ?';
        $dbStmt = $this->pdo->prepare($sql);
        $dbStmt->bindValue(1, $id);
        $dbStmt->execute();

        foreach ($questions as $questionId) {
            $sql = 'INSERT INTO quiz_question_template (quiz_template_id, question_template_id) VALUES (:quizId, :questionId)';
            $dbStmt = $this->pdo->prepare($sql);
            $dbStmt->bindParam(':quizId', $id);
            $dbStmt->bindParam('questionId', $questionId);
            $dbStmt->execute();
        }
    }
}
