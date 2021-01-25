<?php

declare(strict_types=1);

namespace Kenjis\CI3Compatible\Database;

use CodeIgniter\Database\Forge;
use Config\Database;

class CI_DB_forge
{
    /** @var Forge */
    protected $forge;

    public function __construct()
    {
        $this->forge = Database::forge();
    }

    /**
     * Add Field
     *
     * @param   array $field
     *
     * @return  CI_DB_forge
     */
    public function add_field(array $field): self
    {
        $this->forge->addField($field);

        return $this;
    }

    /**
     * Add Key
     *
     * @param   string $key
     * @param   bool   $primary
     *
     * @return  CI_DB_forge
     */
    public function add_key(string $key, bool $primary = false): self
    {
        $this->forge->addKey($key, $primary);

        return $this;
    }

    /**
     * Create Table
     *
     * @param   string $table         Table name
     * @param   bool   $if_not_exists Whether to add IF NOT EXISTS condition
     * @param   array  $attributes    Associative array of table attributes
     *
     * @return  bool
     */
    public function create_table(string $table, bool $if_not_exists = false, array $attributes = [])
    {
        return $this->forge->createTable($table, $if_not_exists, $attributes);
    }

    /**
     * Drop Table
     *
     * @param   string $table_name Table name
     * @param   bool   $if_exists  Whether to add an IF EXISTS condition
     *
     * @return  bool
     */
    public function drop_table(string $table_name, bool $if_exists = false)
    {
        return $this->forge->dropTable($table_name, $if_exists);
    }
}
