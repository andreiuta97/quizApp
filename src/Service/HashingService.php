<?php


namespace QuizApp\Service;


class HashingService
{
    const PASSWORD_ENCRYPT = PASSWORD_BCRYPT;

    /**
     * @var string
     */
    private $algorithm;

    public function __construct(string $algorithm)
    {
        $this->algorithm = $algorithm;
    }

    public function hash(string $password): ?string
    {
        return password_hash($password, $this->algorithm);
    }

    public function verify(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }
}