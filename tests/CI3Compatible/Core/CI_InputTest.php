<?php

declare(strict_types=1);

namespace Fluent\Legacy\Core;

use Fluent\Legacy\Exception\NotSupportedException;
use Fluent\Legacy\TestSupport\TestCase;

class CI_InputTest extends TestCase
{
    /** @var CI_Input */
    private $input;

    public function setUp(): void
    {
        parent::setUp();

        $_POST = [];
        $_GET = [];

        $this->input = new CI_Input();
    }

    public function test_get(): void
    {
        $_GET['q'] = 'abc';

        $val = $this->input->get('q');

        $this->assertSame('abc', $val);
    }

    public function test_post(): void
    {
        $_POST['q'] = 'abc';

        $val = $this->input->post('q');

        $this->assertSame('abc', $val);
    }

    public function test_server(): void
    {
        $val = $this->input->server('CI_ENVIRONMENT');

        $this->assertSame('testing', $val);
    }

    public function test_server_xss_clean(): void
    {
        $this->expectException(NotSupportedException::class);

        $this->input->server('CI_ENVIRONMENT', true);
    }
}
