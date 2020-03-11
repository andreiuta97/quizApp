<?php


namespace QuizApp\Repository;


use ReallyOrm\Repository\AbstractRepository;

class AnswerTemplateRepository extends AbstractRepository
{
    public function getTableName(): string
    {
        return 'answer_template';
    }
}