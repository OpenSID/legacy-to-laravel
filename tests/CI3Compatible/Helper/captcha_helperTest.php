<?php

declare(strict_types=1);

namespace Fluent\Legacy\Helper;

class captcha_helperTest extends HelperTestCase
{
    public function test_create_captcha(): void
    {
        $this->loadHelper('captcha');

        $data = [
            'word'      => 'abcd',
            'img_path'  => __DIR__ . '/',
            'img_url'   => 'http://example.com/captcha/',
        ];
        $cap = create_captcha($data);

        $file = __DIR__ . '/' . $cap['filename'];
        $this->assertSame($data['word'], $cap['word']);
        $this->assertFileExists($file);

        unlink($file);
    }
}
