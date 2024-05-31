<?php

/**
 * Class CustPlantAttrModel
 * 
 * Manages custom plant specific attributes
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

            $globals = CustAttrSchemaModel::getAll()?->asArray();

            $data = static::raw('SELECT * FROM `' . self::tableName() . '` WHERE plant = ?', [$plantId]);
            foreach ($data as $item) {
                $entry = new stdClass();
                $entry->id = $item->get('id');
                $entry->plant = $item->get('plant');
                $entry->label = $item->get('label');
                $entry->datatype = $item->get('datatype');
                $entry->content = static::interpretContent($item->get('content'), $item->get('datatype'));
                $entry->global = UtilsModule::in_array_stdclass($item->get('label'), $globals, 'label');

                $result[] = $entry;
            }
            
            if (is_array($globals)) {
                foreach ($globals as $global) {
                    if (!UtilsModule::in_array_stdclass($global['label'], $result, 'label')) {
                        $entry = new stdClass();
                        $entry->id = 0;
                        $entry->plant = $plantId;
                        $entry->label = $global['label'];
                        $entry->datatype = $global['datatype'];
                        $entry->content = null;
                        $entry->global = true;

                        $result[] = $entry;
                    }
                }
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
     * @param $api
     * @return int
     * @throws \Exception
     */
    public static function addAttribute($plant, $label, $datatype, $content, $api = false)
    {
        try {
            if (!$api) {
                $user = UserModel::getAuthUser();
                if (!$user) {
                    throw new \Exception('Invalid user');
                }
            }

            static::raw('INSERT INTO `' . self::tableName() . '` (plant, label, datatype, content) VALUES(?, ?, ?, ?)', [
                $plant, $label, $datatype, static::interpretContent($content, $datatype)
            ]);

            if (!$api) {
                LogModel::addLog($user->get('id'), $plant, '[add] ' . $label . ' (' . $datatype . ')', $content, url('/plants/details/' . $plant));
            }

            $new_entry = static::raw('SELECT * FROM `' . self::tableName() . '` ORDER BY id DESC LIMIT 1')->first();
            
            return ($new_entry) ? $new_entry->get('id') : 0;
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
     * @param $api
     * @return void
     * @throws \Exception
     */
    public static function editAttribute($id, $plant, $label, $datatype, $content, $api = false)
    {
        try {
            if (!$api) {
                $user = UserModel::getAuthUser();
                if (!$user) {
                    throw new \Exception('Invalid user');
                }
            }

            $exists = static::raw('SELECT * FROM `' . self::tableName() . '` WHERE id = ?', [$id])->first();
            if (!$exists) {
                $id = static::addAttribute($plant, $label, $datatype, $content);
            }
            
            static::raw('UPDATE `' . self::tableName() . '` SET label = ?, datatype = ?, content = ? WHERE id = ?', [
                $label, $datatype, static::interpretContent($content, $datatype), $id
            ]);

            if (!$api) {
                LogModel::addLog($user->get('id'), $plant, '[edit] ' . $label . ' (' . $datatype . ')', $content, url('/plants/details/' . $plant));
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $id
     * @param $api
     * @return void
     * @throws \Exception
     */
    public static function removeAttribute($id, $api = false)
    {
        try {
            if (!$api) {
                $user = UserModel::getAuthUser();
                if (!$user) {
                    throw new \Exception('Invalid user');
                }
            }

            $item = static::raw('SELECT * FROM `' . self::tableName() . '` WHERE id = ?', [$id])->first();
            if (!$item) {
                throw new \Exception('Custom plant attribute not found: ' . $id);
            }

            static::raw('DELETE FROM `' . self::tableName() . '` WHERE id = ?', [$id]);

            if (!$api) {
                LogModel::addLog($user->get('id'), $item->get('plant'), '[remove] ' . $item->get('label') . ' (' . $item->get('datatype') . ')', $item->get('content'), url('/plants/details/' . $item->get('plant')));
            }
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