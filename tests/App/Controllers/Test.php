<?php

declare(strict_types=1);

namespace App\Controllers;

use Fluent\Legacy\Core\CI_Controller;

class Test extends CI_Controller
{
    public function index(): void
    {
        echo __METHOD__;
    }

    public function redirect()
    {
        return redirect()->to('/');
    }
}
