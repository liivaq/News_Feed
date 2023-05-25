<?php declare(strict_types=1);

namespace App;

class ArticleValidator
{
    public static function validate(string $title, string $content): array
    {
        $errors = [];

        if (strlen($title) < 3 || strlen($title) > 255) {
            $errors['title'] = 'Title must be between 3 and 255 characters long';
        }

        if (strlen($content) < 10 || strlen($content) > 5000) {
            $errors['content'] = 'Article content must be between 10 and 5000 characters';
        }

        return $errors;
    }


}