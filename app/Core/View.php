<?php declare(strict_types=1);

namespace App\Core;

class View implements Response
{
    private string $templatePath;
    private array $parameters;

    public function __construct(string $templatePath, array $viewParameters)
    {
        $this->templatePath = $templatePath;
        $this->parameters = $viewParameters;
    }

    public function getTemplatePath(): string
    {
        return $this->templatePath;
    }

    public function getParameters(): array
    {
        return $this->parameters;
    }

}