<?php declare(strict_types=1);

namespace App\Services\Article\Modify;

class ModifyResponse
{
    private array $response;

    public function __construct(array $response)
    {
        $this->response = $response;
    }

    public function getResponse(): array
    {
        return $this->response;
    }

}