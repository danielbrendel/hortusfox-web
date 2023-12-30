<?php

/**
 * This class specifies a migration
 */
class VersionModel_Migration {
    private $database = null;
    private $connection = null;

    /**
     * Store the PDO connection handle
     * 
     * @param \PDO $pdo The PDO connection handle
     * @return void
     */
    public function __construct($pdo)
    {
        $this->connection = $pdo;
    }

    /**
     * Called when the table shall be created or modified
     * 
     * @return void
     */
    public function up()
    {
    }

    /**
     * Called when the table shall be dropped
     * 
     * @return void
     */
    public function down()
    {
    }
}