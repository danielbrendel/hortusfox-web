<?php

use chillerlan\QRCode\{QRCode, QROptions};

/**
 * Class PlantsModel
 * 
 * Extensive management of plants
 */ 
class PlantsModel extends \Asatru\Database\Model {
    const PLANT_STATE_GOOD = 'in_good_standing';
    const PLANT_LONG_TEXT_THRESHOLD = 22;
    const PLANT_PLACEHOLDER_FILE = 'placeholder.jpg';
    const PLANT_LAST_UPDATED_AUTHORED_COUNT = 8;
    const PLANT_LIST_MAX_STRLEN = 15;

    static $sorting_list = [
        'name',
        'last_edited_date',
        'last_watered',
        'last_repotted',
        'last_fertilised',
        'health_state',
        'hardy',
        'light_level',
        'humidity',
        'cutting_month',
        'date_of_purchase',
        'history_date'
    ];

    static $sorting_dir = [
        'asc',
        'desc'
    ];

    static $allowed_attributes = [
        'name',
        'scientific_name',
        'knowledge_link',
        'location',
        'tags',
        'photo',
        'last_watered',
        'last_repotted',
        'last_fertilised',
        'lifespan',
        'hardy',
        'cutting_month',
        'date_of_purchase',
        'humidity',
        'light_level',
        'health_state',
        'notes',
        'history',
        'history_date'
    ];

    static $plant_health_states = [
        'in_good_standing' => [
            'localization' => 'app.in_good_standing',
            'icon' => null
        ],

        'overwatered' => [
            'localization' => 'app.overwatered',
            'icon' => 'fas fa-water'
        ],

        'withering' => [
            'localization' => 'app.withering',
            'icon' => 'fab fa-pagelines'
        ],

        'infected' => [
            'localization' => 'app.infected',
            'icon' => 'fas fa-biohazard'
        ],

        'pest_infestation' => [
            'localization' => 'app.pest_infestation',
            'icon' => 'fas fa-bug'
        ],

        'transplant_shock' => [
            'localization' => 'app.transplant_shock',
            'icon' => 'fas fa-trailer'
        ],

        'nutritional_deficiency' => [
            'localization' => 'app.nutritional_deficiency',
            'icon' => 'fas fa-cookie'
        ],

        'sunburn' => [
            'localization' => 'app.sunburn',
            'icon' => 'fas fa-sun'
        ],

        'frostbite' => [
            'localization' => 'app.frostbite',
            'icon' => 'fas fa-snowflake'
        ],

        'root_rot' => [
            'localization' => 'app.root_rot',
            'icon' => 'fas fa-skull'
        ],
    ];

    /**
     * @param $type
     * @return void
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
     * @return void
     * @throws \Exception
     */
    public static function validateDirection($dir)
    {
        if (!in_array($dir, static::$sorting_dir)) {
            throw new \Exception('Invalid sorting direction: ' . $dir);
        }
    }

    /**
     * @param $attribute
     * @return void
     * @throws \Exception
     */
    public static function validateAttribute($attribute)
    {
        if (!in_array($attribute, static::$allowed_attributes)) {
            throw new \Exception('Invalid attribute specified: ' . $attribute);
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

            return static::raw('SELECT * FROM `@THIS` WHERE location = ? AND history = 0 ORDER BY ' . $sorting . ' ' . $direction, [$location]);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $userId
     * @return mixed
     * @throws \Exception
     */
    public static function getAuthoredPlants($userId, $limit = 0)
    {
        try {
            if ($limit == 0) {
                return static::raw('SELECT * FROM `@THIS` WHERE last_edited_user = ? ORDER BY last_edited_date DESC', [$userId]);
            } else {
                return static::raw('SELECT * FROM `@THIS` WHERE last_edited_user = ? ORDER BY last_edited_date DESC LIMIT ' . $limit, [$userId]);
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public static function getLastAddedPlants()
    {
        try {
            return static::raw('SELECT * FROM `@THIS` WHERE history = 0 ORDER BY id DESC LIMIT ' . strval(self::PLANT_LAST_UPDATED_AUTHORED_COUNT));
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public static function getLastAuthoredPlants()
    {
        try {
            return static::raw('SELECT * FROM `@THIS` WHERE history = 0 AND last_edited_user IS NOT NULL ORDER BY last_edited_date DESC LIMIT ' . strval(self::PLANT_LAST_UPDATED_AUTHORED_COUNT));
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
            return static::raw('SELECT * FROM `@THIS` WHERE id = ?', [$id])->first();
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
            return static::raw('SELECT * FROM `@THIS` WHERE health_state <> \'in_good_standing\' AND history = 0 ORDER BY last_edited_date DESC');
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $year
     * @param $limit
     * @param $sorting
     * @param $direction
     * @return mixed
     * @throws \Exception
     */
    public static function getHistory($year = null, $limit = null, $sorting = null, $direction = null)
    {
        try {
            if ($sorting === null) {
                $sorting = 'history_date';
            }

            if ($direction === null) {
                $direction = 'desc';
            }

            static::validateSorting($sorting);
            static::validateDirection($direction);

            $strlimit = '';
            if ($limit) {
                $strlimit = ' LIMIT ' . $limit;
            }

            if ($year !== null) {
                return static::raw('SELECT * FROM `@THIS` WHERE YEAR(history_date) = ? AND history = 1 ORDER BY ' . $sorting . ' ' . $direction . $strlimit, [$year]);
            } else {
                return static::raw('SELECT * FROM `@THIS` WHERE history = 1 ORDER BY ' . $sorting . ' ' . $direction . $strlimit);
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public static function getHistoryYears()
    {
        try {
            return static::raw('SELECT DISTINCT YEAR(history_date) AS history_year FROM `@THIS` WHERE history = 1 AND history_date IS NOT NULL ORDER BY YEAR(history_date) DESC');
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $name
     * @param $location
     * @param $api
     * @return int
     * @throws \Exception
     */
    public static function addPlant($name, $location, $api = false)
    {
        try {
            $user = null;
            
            if (!$api) {
                $user = UserModel::getAuthUser();
                if (!$user) {
                    throw new \Exception('Invalid user');
                }
            }

            if ((isset($_FILES['photo'])) && ($_FILES['photo']['error'] === UPLOAD_ERR_OK)) {
                $file_ext = UtilsModule::getImageExt($_FILES['photo']['tmp_name']);

                if ($file_ext === null) {
                    throw new \Exception('File is not a valid image');
                }

                $file_name = md5(random_bytes(55) . date('Y-m-d H:i:s'));

                move_uploaded_file($_FILES['photo']['tmp_name'], public_path('/img/' . $file_name . '.' . $file_ext));

                if (!UtilsModule::createThumbFile(public_path('/img/' . $file_name . '.' . $file_ext), UtilsModule::getImageType($file_ext, public_path('/img/' . $file_name)), public_path('/img/' . $file_name), $file_ext)) {
                    throw new \Exception('createThumbFile failed');
                }

                $fullFileName = $file_name . '_thumb.' . $file_ext;
            } else {
                $fullFileName = self::PLANT_PLACEHOLDER_FILE;
            }
            
            static::raw('INSERT INTO `@THIS` (name, location, photo, last_edited_user, last_edited_date) VALUES(?, ?, ?, ?, CURRENT_TIMESTAMP)', [
                $name, $location, $fullFileName, $user?->get('id')
            ]);
            
            $query = static::raw('SELECT * FROM `@THIS` ORDER BY id DESC LIMIT 1')->first();

            if (!$api) {
                TextBlockModule::newPlant($name, url('/plants/details/' . $query->get('id')));
                LogModel::addLog($user->get('id'), $location, 'add_plant', $name, url('/plants/details/' . $query->get('id')));
            }

            return $query->get('id');
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $plantId
     * @param $attribute
     * @param $value
     * @param $api
     * @return void
     * @throws \Exception
     */
    public static function editPlantAttribute($plantId, $attribute, $value, $api = false)
    {
        try {
            $user = null;
            
            if (!$api) {
                $user = UserModel::getAuthUser();
                if (!$user) {
                    throw new \Exception('Invalid user');
                }
            }

            static::validateAttribute($attribute);
            
            static::raw('UPDATE `@THIS` SET ' . $attribute . ' = ?, last_edited_user = ?, last_edited_date = CURRENT_TIMESTAMP WHERE id = ?', [($value !== '#null') ? $value : null, $user?->get('id'), $plantId]);
        
            if (!$api) {
                LogModel::addLog($user->get('id'), $plantId, $attribute, $value, url('/plants/details/' . $plantId));
            }

            if (app('system_message_plant_log')) {
                PlantLogModel::addEntry($plantId, '[System] ' . $attribute . ' = ' . $value, true);
            }
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
            
            static::raw('UPDATE `@THIS` SET scientific_name = ?, knowledge_link = ?, last_edited_user = ?, last_edited_date = CURRENT_TIMESTAMP WHERE id = ?', [$text, $link, $user->get('id'), $plantId]);
        
            LogModel::addLog($user->get('id'), $plantId, 'scientific_name|knowledge_link', $text . '|' . ((strlen($link) > 0) ? $link : 'null'), url('/plants/details/' . $plantId));
        
            if (app('system_message_plant_log')) {
                PlantLogModel::addEntry($plantId, '[System] scientific_name = ' . $text);

                if (strlen($link) > 0) {
                    PlantLogModel::addEntry($plantId, '[System] knowledge_link = ' . $link);
                }
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $plantId
     * @param $attribute
     * @param $value
     * @param $api
     * @return void
     * @throws \Exception
     */
    public static function editPlantPhoto($plantId, $attribute, $value, $api = false)
    {
        try {
            $user = null;

            if (!$api) {
                $user = UserModel::getAuthUser();
                if (!$user) {
                    throw new \Exception('Invalid user');
                }
            }

            static::validateAttribute($attribute);

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

            static::raw('UPDATE `@THIS` SET ' . $attribute . ' = ?, last_edited_user = ?, last_edited_date = CURRENT_TIMESTAMP, last_photo_date = CURRENT_TIMESTAMP WHERE id = ?', [$file_name . '_thumb.' . $file_ext, $user?->get('id'), $plantId]);
        
            if (!$api) {
                LogModel::addLog($user->get('id'), $plantId, $attribute, $value, url('/plants/details/' . $plantId));
            }

            if (app('system_message_plant_log')) {
                PlantLogModel::addEntry($plantId, '[System] ' . $attribute . ' = ' . $file_name . '.' . $file_ext, $api);
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $plantId
     * @param $attribute
     * @param $value
     * @param $api
     * @return void
     * @throws \Exception
     */
    public static function editPlantPhotoURL($plantId, $attribute, $value, $api = false)
    {
        try {
            $user = null;

            if (!$api) {
                $user = UserModel::getAuthUser();
                if (!$user) {
                    throw new \Exception('Invalid user');
                }
            }

            static::validateAttribute($attribute);

            static::raw('UPDATE `@THIS` SET ' . $attribute . ' = ?, last_edited_user = ?, last_edited_date = CURRENT_TIMESTAMP, last_photo_date = CURRENT_TIMESTAMP WHERE id = ?', [$value, $user?->get('id'), $plantId]);
        
            if (!$api) {
                LogModel::addLog($user->get('id'), $plantId, $attribute, $value, url('/plants/details/' . $plantId));
            }

            if (app('system_message_plant_log')) {
                PlantLogModel::addEntry($plantId, '[System] ' . $attribute . ' = ' . $value, $api);
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $plantId
     * @return void
     * @throws \Exception
     */
    public static function clearPreviewPhoto($plantId)
    {
        try {
            $user = UserModel::getAuthUser();
            if (!$user) {
                throw new \Exception('Invalid user');
            }

            $item = static::raw('SELECT * FROM `@THIS` WHERE id = ?', [$plantId])->first();

            if ($item->get('photo') === self::PLANT_PLACEHOLDER_FILE) {
                throw new \Exception('There is no preview photo set');
            }

            static::raw('UPDATE `@THIS` SET photo = ?, last_edited_user = ?, last_edited_date = CURRENT_TIMESTAMP WHERE id = ?', [self::PLANT_PLACEHOLDER_FILE, $user->get('id'), $plantId]);
        
            if (file_exists(public_path() . '/img/' . $item->get('photo'))) {
                unlink(public_path() . '/img/' . $item->get('photo'));
            }
            
            if (file_exists(public_path() . '/img/' . str_replace('_thumb', '', $item->get('photo')))) {
                unlink(public_path() . '/img/' . str_replace('_thumb', '', $item->get('photo')));
            }

            LogModel::addLog($user->get('id'), $plantId, 'photo', self::PLANT_PLACEHOLDER_FILE, url('/plants/details/' . $plantId));

            if (app('system_message_plant_log')) {
                PlantLogModel::addEntry($plantId, '[System] photo = ' . self::PLANT_PLACEHOLDER_FILE);
            }
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
            return static::raw('SELECT COUNT(*) as count FROM `@THIS` WHERE history = 0')->first()->get('count');
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

            $query = 'SELECT * FROM `@THIS` ';
            $hasAny = false;

            $args = [];

            if (substr($text, 0, 1) === '#') {
                $text = ltrim(substr($text, 1), '0');

                return static::raw('SELECT * FROM `@THIS` WHERE id = ? LIMIT 1', [$text]);
            }

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
            static::raw('UPDATE `@THIS` SET last_watered = CURRENT_TIMESTAMP WHERE location = ?', [$location]);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $location
     * @return void
     * @throws \Exception
     */
    public static function updateLastRepotted($location)
    {
        try {
            static::raw('UPDATE `@THIS` SET last_repotted = CURRENT_TIMESTAMP WHERE location = ?', [$location]);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $location
     * @return void
     * @throws \Exception
     */
    public static function updateLastFertilised($location)
    {
        try {
            static::raw('UPDATE `@THIS` SET last_fertilised = CURRENT_TIMESTAMP WHERE location = ?', [$location]);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $plantId
     * @return void
     * @throws \Exception
     */
    public static function markHistorical($plantId)
    {
        try {
            $user = UserModel::getAuthUser();
            if (!$user) {
                throw new \Exception('Invalid user');
            }

            $plant = PlantsModel::getDetails($plantId);

            static::raw('UPDATE `@THIS` SET history = 1, history_date = CURRENT_TIMESTAMP WHERE id = ?', [$plantId]);

            LogModel::addLog($user->get('id'), $plant->get('name'), 'mark_historical', '', url('/plants/history'));
            TextBlockModule::plantToHistory($plant->get('name'), url('/plants/history'));

            if (app('system_message_plant_log')) {
                PlantLogModel::addEntry($plantId, '[System] history = 1');
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $plantId
     * @return void
     * @throws \Exception
     */
    public static function unmarkHistorical($plantId)
    {
        try {
            $user = UserModel::getAuthUser();
            if (!$user) {
                throw new \Exception('Invalid user');
            }

            $plant = PlantsModel::getDetails($plantId);

            static::raw('UPDATE `@THIS` SET history = 0, history_date = NULL WHERE id = ?', [$plantId]);

            LogModel::addLog($user->get('id'), $plant->get('name'), 'historical_restore', '', url('/plants/details/' . $plantId));
            TextBlockModule::plantFromHistory($plant->get('name'), url('/plants/details/' . $plantId));

            if (app('system_message_plant_log')) {
                PlantLogModel::addEntry($plantId, '[System] history = 0');
            }
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

            if ($plant->get('photo') !== self::PLANT_PLACEHOLDER_FILE) {
                if (file_exists(public_path('/img/' . $plant->get('photo')))) {
                    unlink(public_path('/img/' . $plant->get('photo')));
                }

                $original_photo = str_replace('_thumb', '', $plant->get('photo'));

                if (file_exists(public_path('/img/' . $original_photo))) {
                    unlink(public_path('/img/' . $original_photo));
                }
            }

            PlantPhotoModel::clearForPlant($plantId);

            static::raw('DELETE FROM `@THIS` WHERE id = ?', [$plantId]);

            LogModel::addLog($user->get('id'), $plant->get('name'), 'remove_plant', '');
            TextBlockModule::deletePlant($plant->get('name'));
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
            static::raw('UPDATE `@THIS` SET location = ? WHERE location = ?', [
                $to, $from
            ]);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $plantId
     * @return void
     * @throws \Exception
     */
    public static function setUpdated($plantId)
    {
        try {
            $user = UserModel::getAuthUser();
            if (!$user) {
                throw new \Exception('Invalid user');
            }

            static::raw('UPDATE `@THIS` SET last_edited_user = ?, last_edited_date = CURRENT_TIMESTAMP WHERE id = ?', [
                $user->get('id'), (int)$plantId
            ]);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $id
     * @return int
     * @throws \Exception
     */
    public static function getPlantCount($id)
    {
        try {
            return static::raw('SELECT COUNT(*) AS count FROM `@THIS` WHERE location = ? AND history = 0', [$id])->first()->get('count');
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $id
     * @return int
     * @throws \Exception
     */
    public static function getDangerCount($id)
    {
        try {
            return static::raw('SELECT COUNT(*) AS count FROM `@THIS` WHERE location = ? AND health_state <> ? AND history = 0', [
                $id, 'in_good_standing'
            ])->first()->get('count');
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $id
     * @return int
     * @throws \Exception
     */
    public static function clonePlant($id)
    {
        try {
            $user = UserModel::getAuthUser();
            if (!$user) {
                throw new \Exception('Invalid user');
            }

            $source = static::raw('SELECT * FROM `@THIS` WHERE id = ?', [$id])->first();
            if (!$source) {
                throw new \Exception('Plant with ID not found: ' . $id);
            }

            $updated_tags = $source->get('tags');

            if (strpos($source->get('tags'), strtolower($source->get('name'))) === false) {
                $updated_tags = $source->get('tags') . ' ' . strtolower($source->get('name'));

                static::raw('UPDATE `@THIS` SET tags = ?, clone_num = ? WHERE id = ?', [
                    $updated_tags, 0, $source->get('id')
                ]);
            }

            if (file_exists(public_path() . '/img/' . $source->get('photo'))) {
                $target_base_name = md5(random_bytes(55) . date('Y-m-d H:i:s'));
                $target_thumb_name = $target_base_name . '_thumb.' . pathinfo($source->get('photo'), PATHINFO_EXTENSION);
                $target_original_name = $target_base_name . '.' . pathinfo($source->get('photo'), PATHINFO_EXTENSION);
                copy(public_path() . '/img/' . $source->get('photo'), public_path() . '/img/' . $target_thumb_name);
                copy(public_path() . '/img/' . str_replace('_thumb', '', $source->get('photo')), public_path() . '/img/' . $target_original_name);
            } else {
                $target_thumb_name = $source->get('photo');
            }

            $clone_origin = null;
            if ($source->get('clone_num')) {
                $clone_origin = $source->get('clone_origin');
            } else {
                $clone_origin = $source->get('id');
            }

            static::raw('INSERT INTO `@THIS` (name, scientific_name, knowledge_link, tags, location, photo, last_watered, last_repotted, last_fertilised, perennial, hardy, cutting_month, date_of_purchase, humidity, light_level, health_state, notes, last_edited_user, last_edited_date, clone_num, clone_origin) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)', [
                $source->get('name'), $source->get('scientific_name'), $source->get('knowledge_link'), $updated_tags, $source->get('location'), $target_thumb_name, $source->get('last_watered'), $source->get('last_repotted'), $source->get('last_fertilised'), $source->get('perennial'), $source->get('hardy'), $source->get('cutting_month'), $source->get('date_of_purchase'), $source->get('humidity'), $source->get('light_level'), $source->get('health_state'), $source->get('notes'), $user->get('id'), date('Y-m-d H:i:s'), static::getNameCount($source->get('name')), $clone_origin
            ]);

            $clone = static::raw('SELECT * FROM `@THIS` ORDER BY id DESC LIMIT 1')->first();

            return $clone->get('id');
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $id
     * @return mixed
     * @throws \Exception
     */
    public static function findOffspring($id)
    {
        try {
            return static::raw('SELECT * FROM `@THIS` WHERE clone_origin = ?', [$id]);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $name
     * @return int
     * @throws \Exception
     */
    public static function getNameCount($name)
    {
        try {
            $result = static::raw('SELECT COUNT(name) AS `count` FROM `@THIS` WHERE name = ?', [$name])->first();
            return $result->get('count');
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $id
     * @return mixed
     * @throws \Exception
     */
    public static function generateQRCode($id)
    {
        try {
            $plant = static::raw('SELECT * FROM `@THIS` WHERE id = ?', [$id])->first();
            if (!$plant) {
                throw new \Exception('Plant not found: ' . $id);
            }

            $options = new QROptions();
            $options->invertMatrix = true;

            $oqr = new QRCode($options);
			return $oqr->render(url('/plants/details/' . $plant->get('id')));
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $location
     * @param $limit
     * @param $from
     * @param $sort
     * @return mixed
     * @throws \Exception
     */
    public static function getPlantList($location, $limit = null, $from = null, $sort = null)
    {
        try {
            if (($limit !== null) && (is_numeric($limit))) {
                $limit = ' LIMIT ' . $limit;
            }

            if ($sort !== null) {
                if ($sort === 'asc') {
                    $sort = ' ORDER BY id ASC ';
                } else if ($sort === 'desc') {
                    $sort = ' ORDER BY id DESC ';
                }
            }

            if (($from !== null) && (is_numeric($from))) {
                return static::raw('SELECT * FROM `@THIS` WHERE location = ? AND id > ?' . $sort . $limit, [$location, $from]);
            } else {
                return static::raw('SELECT * FROM `@THIS` WHERE location = ?' . $sort . $limit, [$location]);
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $location
     * @param $include
     * @return mixed
     * @throws \Exception
     */
    public static function getSpecificInfo($location, $include = 'id')
    {
        try {
            return static::raw('SELECT ' . $include . ' FROM `@THIS` WHERE location = ? AND history = 0', [$location]);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @return array
     * @throws \Exception
     */
    public static function getDistinctTags()
    {
        try {
            $sql = '
                WITH RECURSIVE tag_split AS (
                    SELECT 
                        SUBSTRING_INDEX(tags, " ", 1) AS tag,
                        SUBSTRING(tags, LOCATE(" ", tags) + 1) AS remaining
                    FROM `@THIS`
                    WHERE tags <> ""

                    UNION ALL

                    SELECT 
                        SUBSTRING_INDEX(remaining, " ", 1),
                        SUBSTRING(remaining, LOCATE(" ", remaining) + 1)
                    FROM tag_split
                    WHERE remaining LIKE "% %"
                )
                SELECT DISTINCT tag FROM tag_split WHERE tag <> "";
            ';

            return static::raw($sql);
        } catch (\Exception) {
            throw $e;
        }
    }
}