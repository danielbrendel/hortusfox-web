<?php

/**
 * This class specifies a migration
 */
class PlantsModel_Migration {
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
        $this->database = new Asatru\Database\Migration('plants', $this->connection);
        $this->database->drop();
        $this->database->add('id INT NOT NULL AUTO_INCREMENT PRIMARY KEY');
        $this->database->add('name VARCHAR(512) NOT NULL');
        $this->database->add('scientific_name VARCHAR(512) NULL');
        $this->database->add('knowledge_link VARCHAR(512) NULL');
        $this->database->add('location INT NOT NULL');
        $this->database->add('tags VARCHAR(1024) NOT NULL DEFAULT \'\'');
        $this->database->add('photo VARCHAR(512) NOT NULL');
        $this->database->add('last_watered DATETIME NULL');
        $this->database->add('last_repotted DATETIME NULL');
        $this->database->add('last_fertilised DATETIME NULL');
        $this->database->add('perennial BOOLEAN NULL');
        $this->database->add('annual BOOLEAN NULL');
        $this->database->add('cutting_month INT NULL');
        $this->database->add('date_of_purchase DATETIME NULL');
        $this->database->add('humidity INT NULL');
        $this->database->add('light_level VARCHAR(512) NULL');
        $this->database->add('health_state VARCHAR(512) NOT NULL DEFAULT \'in_good_standing\'');
        $this->database->add('notes TEXT NULL');
        $this->database->add('history BOOLEAN NOT NULL DEFAULT 0');
        $this->database->add('history_date TIMESTAMP NULL');
        $this->database->add('last_edited_user INT NULL');
        $this->database->add('last_edited_date DATETIME NULL');
        $this->database->add('clone_num INT NULL');
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