<?php

/**
 * This class extends the base model class and represents your associated table
 */ 
class CustPlantAttrModel extends \Asatru\Database\Model {
    static $data_types = [
        'bool',
        'int',
        'double',
        'string',
        'datetime'
    ];

    /**
     * @param $plantId
     * @return mixed
     * @throws \Exception
     */
    public static function getForPlant($plantId)
    {
        try {
            $result = [];

            $data = static::raw('SELECT * FROM `' . self::tableName() . '` WHERE plant = ?', [$plantId]);
            foreach ($data as $item) {
                $entry = new stdClass();
                $entry->id = $item->get('id');
                $entry->plant = $item->get('plant');
                $entry->label = $item->get('label');
                $entry->datatype = $item->get('datatype');
                $entry->content = static::interpretContent($item->get('content'), $item->get('datatype'));

                $result[] = $entry;
            }

            return $result;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $content
     * @param $datatype
     * @return mixed
     * @throws \Exception
     */
    public static function interpretContent($content, $datatype)
    {
        try {
            if (!in_array($datatype, self::$data_types)) {
                throw new \Exception('Invalid data type: ' . $datatype);
            }

            if ($datatype === 'bool') {
                return (bool)$content;
            } else if ($datatype === 'int') {
                return (int)$content;
            } else if ($datatype === 'double') {
                return (double)$content;
            } else if ($datatype === 'string') {
                return (string)$content;
            } else if ($datatype === 'datetime') {
                return date('Y-m-d', strtotime((string)$content));
            } else {
                return null;
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $plant
     * @param $label
     * @param $datatype
     * @param $content
     * @return void
     * @throws \Exception
     */
    public static function addAttribute($plant, $label, $datatype, $content)
    {
        try {
            static::raw('INSERT INTO `' . self::tableName() . '` (plant, label, datatype, content) VALUES(?, ?, ?, ?)', [
                $plant, $label, $datatype, static::interpretContent($content, $datatype)
            ]);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $id
     * @param $plant
     * @param $label
     * @param $datatype
     * @param $content
     * @return void
     * @throws \Exception
     */
    public static function editAttribute($id, $plant, $label, $datatype, $content)
    {
        try {
            static::raw('UPDATE `' . self::tableName() . '` SET label = ?, datatype = ?, content = ? WHERE id = ?', [
                $label, $datatype, static::interpretContent($content, $datatype), $id
            ]);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $id
     * @return void
     * @throws \Exception
     */
    public static function removeAttribute($id)
    {
        try {
            static::raw('DELETE FROM `' . self::tableName() . '` WHERE id = ?', [$id]);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Return the associated table name of the migration
     * 
     * @return string
     */
    public static function tableName()
    {
        return 'custplantattr';
    }
}