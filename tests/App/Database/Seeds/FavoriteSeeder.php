<?php

declare(strict_types=1);

namespace App\Database\Seeds;

use Fluent\Legacy\Library\Seeder;

class FavoriteSeeder extends Seeder
{
    /** @var string */
    private $table = 'favorite';

    /** @var string[] */
    protected $depends = [
        __DIR__ => 'CategorySeeder',
    ];

    public function run()
    {
        echo __METHOD__ . ": $this->table\n";
    }
}
