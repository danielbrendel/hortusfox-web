<?php

/**
 * Class CacheModel_Migration
 */
class CacheModel_Migration {
	private $database = null;
	private $connection = null;

	/**
	 * Construct class and store PDO connection handle
	 * 
	 * @param \PDO $pdo
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
		$this->database = new Asatru\Database\Migration('CacheModel', $this->connection);
		$this->database->drop();
		$this->database->add('id INT NOT NULL AUTO_INCREMENT PRIMARY KEY');
		$this->database->add('ident VARCHAR(260) NOT NULL');
		$this->database->add('value BLOB NULL');
		$this->database->add('updated_at TIMESTAMP');
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
		$this->database = new Asatru\Database\Migration('CacheModel', $this->connection);
		$this->database->drop();
	}
}