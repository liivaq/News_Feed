<?php declare(strict_types=1);

namespace App;

use App\Core\Session;

class Validator
{
    public static function article(string $title, string $body): bool
    {
        if (self::string($title, 3, 255)) {
            Session::flash('errors', 'Title must be between 3 and 255 characters long');
        }

        if (self::string($body, 10, 5000)) {
            Session::flash('errors', 'Article content must be between 10 and 5000 characters');
        }

        return Session::has('errors');
    }

    public static function registrationForm(
        string $email,
        string $username,
        string $password,
        string $passwordRepeat): bool
    {

        if(self::string($password, 3, 255)){
            Session::flash('errors', 'Password must be at least 8 characters');
        }

        if(self::string($username, 3, 255)){
            Session::flash('errors', 'Username must be at least 3 characters');
        }

        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            Session::flash('errors', 'Please enter a valid email address');
        }

        if($password !== $passwordRepeat){
            Session::flash('errors', 'Passwords do not match');
        }

        return Session::has('errors');

    }

    public static function comment(string $title, string $body): bool
    {
        if (self::string($title, 3, 100)) {
            Session::flash('errors', 'Title must be between 3 and 100 characters long');
        }

        if (self::string($body, 10, 500)) {
            Session::flash('errors', 'Comment must be between 10 and 500 characters');
        }

        return Session::has('errors');
    }

    private static function string(string $string, int $min, int $max): bool
    {
        return strlen(trim($string)) < $min || strlen(trim($string)) > $max;
    }
}