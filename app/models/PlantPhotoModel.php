<?php

/**
 * Class PlantPhotoModel
 * 
 * Manages the photo galleries of plants
 */ 
class PlantPhotoModel extends \Asatru\Database\Model {
    /**
     * @param $plantId
     * @param $label
     * @param $api
     * @return int
     * @throws \Exception
     */
    public static function uploadPhoto($plantId, $label, $api = false)
    {
        try {
            $user = UserModel::getAuthUser();
            if ((!$user) && (!$api)) {
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

            static::raw('INSERT INTO `' . self::tableName() . '` (plant, author, thumb, original, label) VALUES(?, ?, ?, ?, ?)', [
                $plantId, (($user) ? $user->get('id') : 0), $file_name . '_thumb.' . $file_ext, $file_name . '.' . $file_ext, $label
            ]);

            if (!$api) {
                LogModel::addLog($user->get('id'), $plantId, 'add_gallery_photo', $label, url('/plants/details/' . $plantId . '#plant-gallery-photo-anchor'));

                if (app('system_message_plant_log')) {
                    PlantLogModel::addEntry($plantId, '[System] add_gallery_photo: ' . $label . ' = ' . $file_name . '.' . $file_ext);
                }
            }

            $recent = static::raw('SELECT * FROM `' . self::tableName() . '` ORDER BY id DESC LIMIT 1')->first();
            if ($recent) {
                return $recent->get('id');
            }

            return 0;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $plantId
     * @param $label
     * @param $photo
     * @param $api
     * @return int
     * @throws \Exception
     */
    public static function addPhotoURL($plantId, $label, $photo, $api = false)
    {
        try {
            $user = UserModel::getAuthUser();
            if ((!$user) && (!$api)) {
                throw new \Exception('Invalid user');
            }

            static::raw('INSERT INTO `' . self::tableName() . '` (plant, author, thumb, original, label) VALUES(?, ?, ?, ?, ?)', [
                $plantId, (($user) ? $user->get('id') : 0), $photo, $photo, $label
            ]);

            if (!$api) {
                LogModel::addLog($user->get('id'), $plantId, 'add_gallery_photo', $label, url('/plants/details/' . $plantId . '#plant-gallery-photo-anchor'));

                if (app('system_message_plant_log')) {
                    PlantLogModel::addEntry($plantId, '[System] add_gallery_photo: ' . $label . ' = ' . $photo);
                }
            }

            $recent = static::raw('SELECT * FROM `' . self::tableName() . '` ORDER BY id DESC LIMIT 1')->first();
            if ($recent) {
                return $recent->get('id');
            }

            return 0;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $plantId
     * @return mixed
     * @throws \Exception
     */
    public static function getPlantGallery($plantId)
    {
        try {
            return static::raw('SELECT * FROM `' . self::tableName() . '` WHERE plant = ? ORDER BY id DESC', [$plantId]);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $photo
     * @param $api
     * @return void
     * @throws \Exception
     */
    public static function removePhoto($photo, $api = false)
    {
        try {
            $user = UserModel::getAuthUser();
            if ((!$user) && (!$api)) {
                throw new \Exception('Invalid user');
            }

            $photo_data = static::raw('SELECT * FROM `' . self::tableName() . '` WHERE id = ?', [$photo])->first();

            $plant = PlantsModel::getDetails($photo_data->get('plant'));

            if (file_exists(public_path('/img/' . $photo_data->get('original')))) {
                unlink(public_path('/img/' . $photo_data->get('original')));
            }

            if (file_exists(public_path('/img/' . $photo_data->get('thumb')))) {
                unlink(public_path('/img/' . $photo_data->get('thumb')));
            }

            static::raw('DELETE FROM `' . self::tableName() . '` WHERE id = ?', [$photo]);

            if (!$api) {
                LogModel::addLog($user->get('id'), $plant->get('name'), 'remove_gallery_photo', $photo_data->get('label'), url('/plants/details/' . $plant->get('id') . '#plant-gallery-photo-anchor'));

                if (app('system_message_plant_log')) {
                    PlantLogModel::addEntry($plant->get('id'), '[System] remove_gallery_photo: ' . $photo_data->get('label'));
                }
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
    public static function clearForPlant($plantId)
    {
        try {
            $rows = static::raw('SELECT * FROM `' . self::tableName() . '` WHERE plant = ?', [$plantId]);
            foreach ($rows as $row) {
                if (file_exists(public_path('/img/' . $row->get('original')))) {
                    unlink(public_path('/img/' . $row->get('original')));
                }

                if (file_exists(public_path('/img/' . $row->get('thumb')))) {
                    unlink(public_path('/img/' . $row->get('thumb')));
                }

                static::raw('DELETE FROM `' . self::tableName() . '` WHERE id = ?', [$row->get('id')]);
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $id
     * @return mixed
     * @throws \Exception
     */
    public static function getItem($id)
    {
        try {
            return static::raw('SELECT * FROM `' . self::tableName() . '` WHERE id = ?', [$id])->first();
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $id
     * @param $label
     * @param $api
     * @return void
     * @throws \Exception
     */
    public static function editLabel($id, $label, $api = false)
    {
        try {
            $photo_data = static::raw('SELECT * FROM `' . self::tableName() . '` WHERE id = ?', [$id])->first();
            $plant = PlantsModel::getDetails($photo_data->get('plant'));

            static::raw('UPDATE `' . self::tableName() . '` SET label = ? WHERE id = ?', [$label, $id]);

            if ((app('system_message_plant_log')) && (!$api)) {
                PlantLogModel::addEntry($plant->get('id'), '[System] edit_gallery_photo: \'' . $photo_data->get('label') . '\' to \'' . $label . '\'');
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $plant
     * @param $thumb
     * @param $original
     * @param $label
     */
    public static function addCustom($plant, $thumb, $original, $label)
    {
        try {
            $user = UserModel::getAuthUser();
            if ((!$user) && (!$api)) {
                throw new \Exception('Invalid user');
            }

            static::raw('INSERT INTO `' . self::tableName() . '` (plant, author, thumb, original, label) VALUES (?, ?, ?, ?, ?)', [$plant, (($user) ? $user->get('id') : 0), $thumb, $original, $label]);
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
        return 'plantphotos';
    }
}