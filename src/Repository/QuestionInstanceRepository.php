<?php


namespace QuizApp\Repository;


use ReallyOrm\Repository\AbstractRepository;

class QuestionInstanceRepository extends AbstractRepository
{
    public function getTableName(): string
    {
        return 'question_instance';
    }
}