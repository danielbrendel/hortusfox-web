<?php

/**
 * This class specifies a migration
 */
class AppModel_Migration {
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
        $this->database = new Asatru\Database\Migration('AppModel', $this->connection);
        $this->database->drop();
        $this->database->add('id INT NOT NULL AUTO_INCREMENT PRIMARY KEY');
        $this->database->add('workspace VARCHAR(512) NOT NULL DEFAULT \'My home\'');
        $this->database->add('language VARCHAR(512) NOT NULL DEFAULT \'en\'');
        $this->database->add('timezone VARCHAR(512) NULL');
        $this->database->add('scroller BOOLEAN NOT NULL DEFAULT 1');
        $this->database->add('chat_enable BOOLEAN NOT NULL DEFAULT 1');
        $this->database->add('chat_timelimit INT NOT NULL DEFAULT 5');
        $this->database->add('chat_showusers BOOLEAN NOT NULL DEFAULT 1');
        $this->database->add('chat_indicator BOOLEAN NOT NULL DEFAULT 1');
        $this->database->add('chat_system BOOLEAN NOT NULL DEFAULT 1');
        $this->database->add('history_enable BOOLEAN NOT NULL DEFAULT 1');
        $this->database->add('history_name VARCHAR(512) NULL DEFAULT \'History\'');
        $this->database->add('enable_media_share BOOLEAN NOT NULL DEFAULT 0');
        $this->database->add('cronjob_pw VARCHAR(512) NOT NULL DEFAULT \'\'');
        $this->database->add('overlay_alpha VARCHAR(512) NULL');
        $this->database->add('smtp_fromname VARCHAR(512) NULL');
        $this->database->add('smtp_fromaddress VARCHAR(512) NULL');
        $this->database->add('smtp_host VARCHAR(512) NULL');
        $this->database->add('smtp_port INT NOT NULL DEFAULT 587');
        $this->database->add('smtp_username VARCHAR(512) NULL');
        $this->database->add('smtp_password VARCHAR(512) NULL');
        $this->database->add('smtp_encryption VARCHAR(512) NOT NULL DEFAULT \'tls\'');
        $this->database->add('pwa_enable BOOLEAN NOT NULL DEFAULT 0');
        $this->database->add('owm_enable BOOLEAN NOT NULL DEFAULT 0');
        $this->database->add('owm_api_key VARCHAR(512) NULL');
        $this->database->add('owm_latitude DECIMAL(10, 8) NULL');
        $this->database->add('owm_longitude DECIMAL(11, 8) NULL');
        $this->database->add('owm_unittype VARCHAR(512) NOT NULL DEFAULT \'default\'');
        $this->database->add('owm_cache INT NOT NULL DEFAULT 300');
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