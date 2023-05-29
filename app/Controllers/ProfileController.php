<?php declare(strict_types=1);

namespace App\Controllers;

use App\Core\View;

class ProfileController
{
    public function edit(){
        return new View('edit', []);
    }

}