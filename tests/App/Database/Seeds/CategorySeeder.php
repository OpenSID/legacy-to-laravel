<?php

declare(strict_types=1);

namespace App\Database\Seeds;

use OpenDesa\Legacy\Library\Seeder;

class CategorySeeder extends Seeder
{
    /** @var string */
    private $table = 'category';

    public function run()
    {
        echo __METHOD__ . ": $this->table\n";
    }
}
