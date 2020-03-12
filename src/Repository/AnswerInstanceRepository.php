<?php


namespace QuizApp\Repository;


use ReallyOrm\Repository\AbstractRepository;

class AnswerInstanceRepository extends AbstractRepository
{
    public function getTableName(): string
    {
        return 'answer_instance';
    }
}