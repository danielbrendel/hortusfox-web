<?php

    /*
        Asatru PHP - Model for plants
    */

    /**
     * This class extends the base model class and represents your associated table
     */ 
    class PlantsModel extends \Asatru\Database\Model {
        const PLANT_STATE_GOOD = 'in_good_standing';

        /**
         * @param $location
         * @return mixed
         * @throws \Exception
         */
        public static function getAll($location)
        {
            try {
                return static::raw('SELECT * FROM `' . self::tableName() . '` WHERE location = ? ORDER BY name ASC', [$location]);
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
         * @return int
         * @throws \Exception
         */
        public static function addPlant($name, $location, $perennial, $cutting_month, $date_of_purchase, $humidity, $light_level)
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

                $file_name = md5(random_bytes(55) . date('Y-m-d H:i:s')) . '.' . $file_ext;

                move_uploaded_file($_FILES['photo']['tmp_name'], public_path('/img/' . $file_name));

                static::raw('INSERT INTO `' . self::tableName() . '` (name, location, photo, perennial, cutting_month, date_of_purchase, humidity, light_level, last_edited_user, last_edited_date) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, CURRENT_TIMESTAMP)', [
                    $name, $location, $file_name, $perennial, $cutting_month, $date_of_purchase, $humidity, $light_level, $user->get('id')
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

                static::raw('UPDATE `' . self::tableName() . '` SET ' . $attribute . ' = ?, last_edited_user = ?, last_edited_date = CURRENT_TIMESTAMP WHERE id = ?', [$value, $user->get('id'), $plantId]);
            
                LogModel::addLog($user->get('id'), $plantId, $attribute, $value);
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

                $file_name = md5(random_bytes(55) . date('Y-m-d H:i:s')) . '.' . $file_ext;

                move_uploaded_file($_FILES[$value]['tmp_name'], public_path('/img/' . $file_name));

                static::raw('UPDATE `' . self::tableName() . '` SET ' . $attribute . ' = ?, last_edited_user = ?, last_edited_date = CURRENT_TIMESTAMP WHERE id = ?', [$file_name, $user->get('id'), $plantId]);
            
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
         * @return int
         * @throws \Exception
         */
        public static function getEndangeredCount()
        {
            try {
                return static::raw('SELECT COUNT(*) as count FROM `' . self::tableName() . '` WHERE health_state <> ?', [self::PLANT_STATE_GOOD])->first()->get('count');
            } catch (\Exception $e) {
                throw $e;
            }
        }

        /**
         * @param $text
         * @param $search_name
         * @param $search_tags
         * @param $search_notes
         * @return mixed
         * @throws \Exception
         */
        /*public static function performSearch($text, $search_name, $search_tags, $search_notes)
        {
            try {
                $text = trim(strtolower($text));

                $result = static::where('name', '<>', null);

                if ($search_name) {
                    $result = $result->whereOr('LOWER(name)', 'LIKE', '%' . $text . '%');
                }

                if ($search_tags) {
                    $result = $result->whereOr('LOWER(tags)', 'LIKE', '%' . $text . '%');
                }

                if ($search_notes) {
                    $result = $result->whereOr('LOWER(notes)', 'LIKE', '%' . $text . '%');
                }

                return $result->orderBy('last_edited_date', 'DESC')->get();
            } catch (\Exception $e) {
                throw $e;
            }
        }*/

        /**
         * @param $text
         * @param $search_name
         * @param $search_tags
         * @param $search_notes
         * @return mixed
         * @throws \Exception
         */
        public static function performSearch($text, $search_name, $search_tags, $search_notes)
        {
            try {
                $text = trim(strtolower($text));

                $query = 'SELECT * FROM `' . self::tableName() . '` ';
                $hasAny = false;

                $args = [];

                if ($search_name) {
                    if ($hasAny) {
                        $query .= ' OR WHERE LOWER(name) LIKE ? ';
                    } else {
                        $query .= ' WHERE LOWER(name) LIKE ? ';
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
         * @param $plantId
         * @return void
         * @throws \Exception
         */
        public static function removePhoto($plantId)
        {
            try {
                $user = UserModel::getAuthUser();
                if (!$user) {
                    throw new \Exception('Invalid user');
                }

                $plant = PlantsModel::getDetails($plantId);

                static::raw('DELETE FROM `' . self::tableName() . '` WHERE id = ?', [$plantId]);

                LogModel::addLog($user->get('id'), $plant->get('name'), 'remove_plant', '');
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