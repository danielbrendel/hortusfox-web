<?php

    /*
        Asatru PHP - Model for plants
    */

    /**
     * This class extends the base model class and represents your associated table
     */ 
    class PlantsModel extends \Asatru\Database\Model {
        const PLANT_STATE_GOOD = 'in_good_standing';

        static $sorting_list = [
            'name',
            'last_watered',
            'last_repotted',
            'health_state',
            'perennial',
            'light_level',
            'humidity'
        ];

        static $sorting_dir = [
            'asc',
            'desc'
        ];

        /**
         * @param $type
         * @throws \Exception
         */
        public static function validateSorting($type)
        {
            if (!in_array($type, static::$sorting_list)) {
                throw new \Exception('Invalid sorting type: ' . $type);
            }
        }

        /**
         * @param $dir
         * @throws \Exception
         */
        public static function validateDirection($dir)
        {
            if (!in_array($dir, static::$sorting_dir)) {
                throw new \Exception('Invalid sorting direction: ' . $dir);
            }
        }

        /**
         * @param $location
         * @param $sorting
         * @param $direction
         * @return mixed
         * @throws \Exception
         */
        public static function getAll($location, $sorting = null, $direction = null)
        {
            try {
                if ($sorting === null) {
                    $sorting = 'name';
                }

                if ($direction === null) {
                    $direction = 'asc';
                }

                static::validateSorting($sorting);
                static::validateDirection($direction);

                return static::raw('SELECT * FROM `' . self::tableName() . '` WHERE location = ? ORDER BY ' . $sorting . ' ' . $direction, [$location]);
            } catch (\Exception $e) {
                throw $e;
            }
        }

        /**
         * @param $userId
         * @return mixed
         * @throws \Exception
         */
        public static function getAuthoredPlants($userId)
        {
            try {
                return static::raw('SELECT * FROM `' . self::tableName() . '` WHERE last_edited_user = ? ORDER BY last_edited_date DESC', [$userId]);
            } catch (\Exception $e) {
                throw $e;
            }
        }

        /**
         * @param $id
         * @return mixed
         * @throws \Exception
         */
        public static function getDetails($id)
        {
            try {
                return static::raw('SELECT * FROM `' . self::tableName() . '` WHERE id = ?', [$id])->first();
            } catch (\Exception $e) {
                throw $e;
            }
        }

        /**
         * @return mixed
         * @throws \Exception
         */
        public static function getWarningPlants()
        {
            try {
                return static::raw('SELECT * FROM `' . self::tableName() . '` WHERE health_state <> \'in_good_standing\' ORDER BY last_edited_date DESC');
            } catch (\Exception $e) {
                throw $e;
            }
        }

        /**
         * @param $name
         * @param $location
         * @param $perennial
         * @param $humidity
         * @param $light_level
         * @return int
         * @throws \Exception
         */
        public static function addPlant($name, $location, $perennial, $humidity, $light_level)
        {
            try {
                $user = UserModel::getAuthUser();
                if (!$user) {
                    throw new \Exception('Invalid user');
                }

                if ((!isset($_FILES['photo'])) || ($_FILES['photo']['error'] !== UPLOAD_ERR_OK)) {
                    throw new \Exception('Errorneous file');
                }

                $file_ext = UtilsModule::getImageExt($_FILES['photo']['tmp_name']);

                if ($file_ext === null) {
                    throw new \Exception('File is not a valid image');
                }

                $file_name = md5(random_bytes(55) . date('Y-m-d H:i:s'));

                move_uploaded_file($_FILES['photo']['tmp_name'], public_path('/img/' . $file_name . '.' . $file_ext));

                if (!UtilsModule::createThumbFile(public_path('/img/' . $file_name . '.' . $file_ext), UtilsModule::getImageType($file_ext, public_path('/img/' . $file_name)), public_path('/img/' . $file_name), $file_ext)) {
                    throw new \Exception('createThumbFile failed');
                }

                static::raw('INSERT INTO `' . self::tableName() . '` (name, location, photo, perennial, humidity, light_level, last_edited_user, last_edited_date) VALUES(?, ?, ?, ?, ?, ?, ?, CURRENT_TIMESTAMP)', [
                    $name, $location, $file_name . '_thumb.' . $file_ext, $perennial, $humidity, $light_level, $user->get('id')
                ]);

                $query = static::raw('SELECT * FROM `' . self::tableName() . '` ORDER BY id DESC LIMIT 1')->first();

                LogModel::addLog($user->get('id'), $location, 'add_plant', $name);

                return $query->get('id');
            } catch (\Exception $e) {
                throw $e;
            }
        }

        /**
         * @param $plantId
         * @param $attribute
         * @param $value
         * @return void
         * @throws \Exception
         */
        public static function editPlantAttribute($plantId, $attribute, $value)
        {
            try {
                $user = UserModel::getAuthUser();
                if (!$user) {
                    throw new \Exception('Invalid user');
                }
                
                static::raw('UPDATE `' . self::tableName() . '` SET ' . $attribute . ' = ?, last_edited_user = ?, last_edited_date = CURRENT_TIMESTAMP WHERE id = ?', [($value !== '#null') ? $value : null, $user->get('id'), $plantId]);
            
                LogModel::addLog($user->get('id'), $plantId, $attribute, $value);
            } catch (\Exception $e) {
                throw $e;
            }
        }

        /**
         * @param $plantId
         * @param $text
         * @param $link
         * @return void
         * @throws \Exception
         */
        public static function editPlantLink($plantId, $text, $link = '')
        {
            try {
                $user = UserModel::getAuthUser();
                if (!$user) {
                    throw new \Exception('Invalid user');
                }

                if (strlen($link) > 0) {
                    if ((strpos($link, 'http://') === false) && (strpos($link, 'https://') === false)) {
                        $link = '';
                    }
                }
                
                static::raw('UPDATE `' . self::tableName() . '` SET scientific_name = ?, knowledge_link = ?, last_edited_user = ?, last_edited_date = CURRENT_TIMESTAMP WHERE id = ?', [$text, $link, $user->get('id'), $plantId]);
            
                LogModel::addLog($user->get('id'), $plantId, 'scientific_name|knowledge_link', $text . '|' . ((strlen($link) > 0) ? $link : 'null'));
            } catch (\Exception $e) {
                throw $e;
            }
        }

        /**
         * @param $plantId
         * @param $attribute
         * @param $value
         * @return void
         * @throws \Exception
         */
        public static function editPlantPhoto($plantId, $attribute, $value)
        {
            try {
                $user = UserModel::getAuthUser();
                if (!$user) {
                    throw new \Exception('Invalid user');
                }

                if ((!isset($_FILES[$value])) || ($_FILES[$value]['error'] !== UPLOAD_ERR_OK)) {
                    throw new \Exception('Errorneous file');
                }

                $file_ext = UtilsModule::getImageExt($_FILES[$value]['tmp_name']);

                if ($file_ext === null) {
                    throw new \Exception('File is not a valid image');
                }

                $file_name = md5(random_bytes(55) . date('Y-m-d H:i:s'));

                move_uploaded_file($_FILES[$value]['tmp_name'], public_path('/img/' . $file_name . '.' . $file_ext));

                if (!UtilsModule::createThumbFile(public_path('/img/' . $file_name . '.' . $file_ext), UtilsModule::getImageType($file_ext, public_path('/img/' . $file_name)), public_path('/img/' . $file_name), $file_ext)) {
                    throw new \Exception('createThumbFile failed');
                }

                static::raw('UPDATE `' . self::tableName() . '` SET ' . $attribute . ' = ?, last_edited_user = ?, last_edited_date = CURRENT_TIMESTAMP WHERE id = ?', [$file_name . '_thumb.' . $file_ext, $user->get('id'), $plantId]);
            
                LogModel::addLog($user->get('id'), $plantId, $attribute, $value);
            } catch (\Exception $e) {
                throw $e;
            }
        }

        /**
         * @return int
         * @throws \Exception
         */
        public static function getCount()
        {
            try {
                return static::raw('SELECT COUNT(*) as count FROM `' . self::tableName() . '`')->first()->get('count');
            } catch (\Exception $e) {
                throw $e;
            }
        }

        /**
         * @param $text
         * @param $search_name
         * @param $search_scientific_name
         * @param $search_tags
         * @param $search_notes
         * @return mixed
         * @throws \Exception
         */
        public static function performSearch($text, $search_name, $search_scientific_name, $search_tags, $search_notes)
        {
            try {
                $text = trim(strtolower($text));

                $query = 'SELECT * FROM `' . self::tableName() . '` ';
                $hasAny = false;

                $args = [];

                if ($search_name) {
                    if ($hasAny) {
                        $query .= ' OR LOWER(name) LIKE ? ';
                    } else {
                        $query .= ' WHERE LOWER(name) LIKE ? ';
                    }

                    $args[] = '%' . $text . '%';
                    $hasAny = true;
                }

                if ($search_scientific_name) {
                    if ($hasAny) {
                        $query .= ' OR LOWER(scientific_name) LIKE ? ';
                    } else {
                        $query .= ' WHERE LOWER(scientific_name) LIKE ? ';
                    }

                    $args[] = '%' . $text . '%';
                    $hasAny = true;
                }

                if ($search_tags) {
                    if ($hasAny) {
                        $query .= ' OR LOWER(tags) LIKE ? ';
                    } else {
                        $query .= ' WHERE LOWER(tags) LIKE ? ';
                    }

                    $args[] = '%' . $text . '%';
                    $hasAny = true;
                }

                if ($search_notes) {
                    if ($hasAny) {
                        $query .= ' OR LOWER(notes) LIKE ? ';
                    } else {
                        $query .= ' WHERE LOWER(notes) LIKE ? ';
                    }

                    $args[] = '%' . $text . '%';
                    $hasAny = true;
                }

                $query .= ' ORDER BY last_edited_date DESC';
                
                return static::raw($query, $args);
            } catch (\Exception $e) {
                throw $e;
            }
        }

        /**
         * @param $location
         * @return void
         * @throws \Exception
         */
        public static function updateLastWatered($location)
        {
            try {
                static::raw('UPDATE `' . self::tableName() . '` SET last_watered = CURRENT_TIMESTAMP WHERE location = ?', [$location]);
            } catch (\Exception $e) {
                throw $e;
            }
        }

        /**
         * @param $plantId
         * @return void
         * @throws \Exception
         */
        public static function removePlant($plantId)
        {
            try {
                $user = UserModel::getAuthUser();
                if (!$user) {
                    throw new \Exception('Invalid user');
                }

                $plant = PlantsModel::getDetails($plantId);

                if (file_exists(public_path('/img/' . $plant->get('photo')))) {
                    unlink(public_path('/img/' . $plant->get('photo')));
                }

                PlantPhotoModel::clearForPlant($plantId);

                static::raw('DELETE FROM `' . self::tableName() . '` WHERE id = ?', [$plantId]);

                LogModel::addLog($user->get('id'), $plant->get('name'), 'remove_plant', '');
            } catch (\Exception $e) {
                throw $e;
            }
        }

        /**
         * @param $from
         * @param $to
         * @return void
         * @throws \Exception
         */
        public static function migratePlants($from, $to)
        {
            try {
                static::raw('UPDATE `' . self::tableName() . '` SET location = ? WHERE location = ?', [
                    $to, $from
                ]);
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
            return 'plants';
        }
    }