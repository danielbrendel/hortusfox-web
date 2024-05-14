<?php

/**
 * This class extends the base model class and represents your associated table
 */ 
class PlantPhotoModel extends \Asatru\Database\Model {
    /**
     * @param $plantId
     * @param $label
     * @return void
     * @throws \Exception
     */
    public static function uploadPhoto($plantId, $label)
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

            static::raw('INSERT INTO `' . self::tableName() . '` (plant, author, thumb, original, label) VALUES(?, ?, ?, ?, ?)', [
                $plantId, $user->get('id'), $file_name . '_thumb.' . $file_ext, $file_name . '.' . $file_ext, $label
            ]);

            LogModel::addLog($user->get('id'), $plantId, 'add_gallery_photo', $label, url('/plants/details/' . $plantId . '#plant-gallery-photo-anchor'));
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
     * @return void
     * @throws \Exception
     */
    public static function removePhoto($photo)
    {
        try {
            $user = UserModel::getAuthUser();
            if (!$user) {
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

            LogModel::addLog($user->get('id'), $plant->get('name'), 'remove_gallery_photo', $photo_data->get('label'), url('/plants/details/' . $plant->get('id') . '#plant-gallery-photo-anchor'));
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
     * @return void
     * @throws \Exception
     */
    public static function editLabel($id, $label)
    {
        try {
            static::raw('UPDATE `' . self::tableName() . '` SET label = ? WHERE id = ?', [$label, $id]);
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