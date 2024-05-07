<?php

/**
 * This class specifies a migration
 */
class UserModel_Migration {
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
        $this->database = new Asatru\Database\Migration('users', $this->connection);
        $this->database->drop();
        $this->database->add('id INT NOT NULL AUTO_INCREMENT PRIMARY KEY');
        $this->database->add('name VARCHAR(512) NOT NULL');
        $this->database->add('email VARCHAR(512) NOT NULL');
        $this->database->add('password VARCHAR(1024) NOT NULL');
        $this->database->add('password_reset VARCHAR(1024) NULL');
        $this->database->add('admin BOOLEAN NOT NULL DEFAULT 0');
        $this->database->add('lang VARCHAR(512) NULL');
        $this->database->add('chatcolor VARCHAR(512) NULL');
        $this->database->add('notes TEXT NULL');
        $this->database->add('theme VARCHAR(512) NULL');
        $this->database->add('show_log BOOLEAN NOT NULL DEFAULT 1');
        $this->database->add('show_calendar_view BOOLEAN NOT NULL DEFAULT 1');
        $this->database->add('show_plants_aoru BOOLEAN NOT NULL DEFAULT 1');
        $this->database->add('show_plant_id BOOLEAN NOT NULL DEFAULT 0');
        $this->database->add('notify_tasks_overdue BOOLEAN NOT NULL DEFAULT 1');
        $this->database->add('notify_tasks_tomorrow BOOLEAN NOT NULL DEFAULT 1');
        $this->database->add('notify_calendar_reminder BOOLEAN NOT NULL DEFAULT 1');
        $this->database->add('last_seen_msg INT NULL');
        $this->database->add('last_typing TIMESTAMP NULL');
        $this->database->add('last_seen_sysmsg INT NULL');
        $this->database->add('last_action TIMESTAMP DEFAULT CURRENT_TIMESTAMP');
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