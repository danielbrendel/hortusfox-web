<?php

/*
    Asatru PHP - Example model
*/

/**
 * Example model class
 */
class ExampleModel extends \Asatru\Database\Model {
    /**
     * Insert some test data
     * 
     * @return void
     */
    public static function testData()
    {
        ExampleModel::raw('INSERT INTO ' . self::tableName() . ' (text) VALUES("Text #1");');
        ExampleModel::raw('INSERT INTO ' . self::tableName() . ' (text) VALUES("Text #2");');
        ExampleModel::raw('INSERT INTO ' . self::tableName() . ' (text) VALUES("Text #3");');
    }

    /**
     * Query text from database
     * 
     * @param int $id
     * @return string
     */
    public static function getText($id)
    {
        $result = ExampleModel::raw('SELECT text FROM ' . self::tableName() . ' WHERE id = ?', array($id));
        
        return $result->get(0)->get('text');
    }

    /**
     * Set text into table
     * 
     * @param int $id
     * @param string $text
     * @return void
     */
    public static function setText($id, $text)
    {
        ExampleModel::raw('UPDATE ' . self::tableName() . ' set text = ? WHERE id = ?;', array($text, $id));
    }

    /**
     * Return the associated table name of the migration
     * 
     * @return string
     */
    public static function tableName()
    {
        return 'example_migration';
    }
}
