<?php

declare(strict_types=1);

namespace Fluent\Legacy\Core;

use CodeIgniter\Exceptions\PageNotFoundException;
use Fluent\Legacy\Exception\NotSupportedException;
use Fluent\Legacy\Exception\RuntimeException;
use Fluent\Legacy\TestSupport\TestCase;

class CommonTest extends TestCase
{
    public function test_show_404_throws_PageNotFoundException(): void
    {
        $this->expectException(PageNotFoundException::class);
        $this->expectExceptionMessage('Not Found: The controller/method pair you requested was not found.');

        show_404('admin/login');
    }

    public function test_show_error_throws_RuntimeException(): void
    {
        $this->expectException(RuntimeException::class);

        show_error('Invalid Input');
    }

    public function test_show_error_throws_NotSupportedException(): void
    {
        $this->expectException(NotSupportedException::class);

        show_error(
            'Invalid Input',
            500,
            'An Error Was Encountered'
        );
    }

    public function test_html_escape(): void
    {
        $string = html_escape('<>&&');
        $this->assertSame('&lt;&gt;&amp;&amp;', $string);
    }

    public function test_html_escaper_throws_NotSupportedException(): void
    {
        $this->expectException(NotSupportedException::class);

        html_escape('<>&&', false);
    }
}
