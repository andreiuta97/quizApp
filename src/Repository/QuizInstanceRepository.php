<?php


namespace QuizApp\Repository;


use ReallyOrm\Repository\AbstractRepository;

class QuizInstanceRepository extends AbstractRepository
{
    public function getResultsData(): array
    {
        $sql = "SELECT quiz_instance.id AS quiz_instance_id, user.name, quiz_template.id AS quiz_template_id, user.id AS user_id FROM quiz_instance 
                    JOIN user ON quiz_instance.user_id = user.id 
                    JOIN quiz_template ON quiz_instance.quiz_template_id = quiz_template.id";
        $dbStmt = $this->pdo->prepare($sql);
        $dbStmt->execute();

        return $dbStmt->fetchAll();
    }
}