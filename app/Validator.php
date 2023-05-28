<?php declare(strict_types=1);

namespace App;

use App\Core\Session;

class Validator
{
    public static function article(string $title, string $content): bool
    {
        if (self::string($title, 3, 255)) {
            Session::flash('errors', 'Title must be between 3 and 255 characters long');
        }

        if (self::string($content, 10, 5000)) {
            Session::flash('errors', 'Article content must be between 10 and 5000 characters');
        }

        return Session::has('errors');
    }

    public static function registrationForm(string $email, string $password, string $passwordRepeat): bool
    {

        if(self::string($password, 3, 255)){
            Session::flash('errors', 'Password must be at least 8 characters');
        }

        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            Session::flash('errors', 'Please enter a valid email address');
        }

        if($password !== $passwordRepeat){
            Session::flash('errors', 'Passwords do not match');
        }

        return Session::has('errors');

    }

    public static function loginForm(string $email, string $password)
    {
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            Session::flash('errors', 'Please enter a valid email address');
        }

        if(self::string($password, 3, 255)){
            Session::flash('errors', 'Password must be at least 8 characters');
        }

        return Session::has('errors');
    }


    private static function string(string $string, int $min, int $max): bool
    {
        return strlen($string) < $min || strlen($string) > $max;
    }
}