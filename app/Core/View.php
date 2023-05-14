<?php

namespace App\Core;

class View
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