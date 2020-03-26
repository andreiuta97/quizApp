<?php


namespace QuizApp\Service;


class HashingService
{
    public function hash(string $password): ?string
    {
        return password_hash($password, PASSWORD_BCRYPT);
    }

    public function verify(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }
}