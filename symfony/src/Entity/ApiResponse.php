<?php

namespace App\Entity;

class ApiResponse
{
    private mixed $data;
    private string $message;
    private int $statusCode;

    public function __construct(mixed $data = null, string $message = 'Success', int $statusCode = 200)
    {
        $this->data = $data;
        $this->message = $message;
        $this->statusCode = $statusCode;
    }

    public function getData(): mixed
    {
        return $this->data;
    }

    public function setData(mixed $data): self
    {
        $this->data = $data;
        return $this;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;
        return $this;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function setStatusCode(int $statusCode): self
    {
        $this->statusCode = $statusCode;
        return $this;
    }
}
