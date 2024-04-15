<?php

/**
 * This class specifies a migration
 */
class InventoryModel_Migration {
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
        $this->database = new Asatru\Database\Migration('inventory', $this->connection);
        $this->database->drop();
        $this->database->add('id INT NOT NULL AUTO_INCREMENT PRIMARY KEY');
        $this->database->add('name VARCHAR(512) NOT NULL');
        $this->database->add('group_ident VARCHAR(512) NOT NULL');
        $this->database->add('description TEXT NULL');
        $this->database->add('location VARCHAR(512) NULL');
        $this->database->add('photo VARCHAR(512) NULL');
        $this->database->add('amount INT NOT NULL DEFAULT 0');
        $this->database->add('last_edited_user INT NULL');
        $this->database->add('last_edited_date DATETIME NULL');
        $this->database->add('created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP');
        $this->database->create();
    }

    /**
     * Called when the table shall be dropped
     * 
     * @return void
     */
    public function down()
    {
        if ($this->database)
            $this->database->drop();
    }
}