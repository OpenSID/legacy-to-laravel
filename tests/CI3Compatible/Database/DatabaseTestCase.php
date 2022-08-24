<?php

declare(strict_types=1);

namespace OpenDesa\Legacy\Database;

use CodeIgniter\Database\BaseConnection;
use Config\Database;
use OpenDesa\Legacy\DatabaseTestHelperTrait;
use OpenDesa\Legacy\TestSupport\TestCase;

abstract class DatabaseTestCase extends TestCase
{
    use DatabaseTestHelperTrait;

    /** @var BaseConnection */
    protected static $connection;

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        self::connectDb();
        static::createTable();
        static::seedData();
    }

    protected static function connectDb(): void
    {
        self::$connection = Database::connect();
    }

    protected static function createTable(): void
    {
    }

    protected static function seedData(): void
    {
    }
}
