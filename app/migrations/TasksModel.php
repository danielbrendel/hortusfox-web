<?php

/**
 * Class TasksModel_Migration
 */
class TasksModel_Migration {
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
        $this->database = new Asatru\Database\Migration('TasksModel', $this->connection);
        $this->database->drop();
        $this->database->add('id INT NOT NULL AUTO_INCREMENT PRIMARY KEY');
        $this->database->add('title VARCHAR(512) NOT NULL');
        $this->database->add('description TEXT NOT NULL');
        $this->database->add('due_date TIMESTAMP NULL');
        $this->database->add('recurring_time INT NULL');
        $this->database->add('recurring_scope VARCHAR(512) NOT NULL DEFAULT \'hours\'');
        $this->database->add('done BOOLEAN NOT NULL DEFAULT 0');
        $this->database->add('created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP');
        $this->database->add('updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP');
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