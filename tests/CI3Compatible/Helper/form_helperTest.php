<?php

declare(strict_types=1);

namespace Kenjis\CI3Compatible\Helper;

use Config\Services;
use Kenjis\CI3Compatible\Exception\NotSupportedException;
use Kenjis\CI3Compatible\TestCase;

class form_helperTest extends TestCase
{
    public function test_validation_errors(): void
    {
        require __DIR__ . '/../../../src/CI3Compatible/Helper/form_helper.php';

        $validation =  Services::validation();
        $validation->reset();
        $validation->setRule('username', 'Username', 'required');
        $data = [];
        $validation->run($data);

        $errorHtml = validation_errors();
        $this->assertStringContainsString(
            'The Username field is required.',
            $errorHtml
        );
    }

    public function test_validation_errors_throws_exception(): void
    {
        $this->expectException(NotSupportedException::class);

        validation_errors('<div>', '</div>');
    }
}
