<?php

/**
 * Class LocationsModel
 * 
 * Manages plant locations
 */ 
class LocationsModel extends \Asatru\Database\Model {
    /**
     * @param $only_active
     * @return mixed
     * @throws \Exception
     */
    public static function getAll($only_active = true)
    {
        try {
            if ($only_active) {
                return static::raw('SELECT * FROM `@THIS` WHERE active = 1 ORDER BY name ASC');
            } else {
                return static::raw('SELECT * FROM `@THIS` ORDER BY name ASC');
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $only_active
     * @param $paginate
     * @param $limit
     */
    public static function getPaginated($only_active = true, $paginate = null, $limit = null)
    {
        try {
            $limit = ((is_numeric($limit) && ($limit > 0)) ? 'LIMIT ' . $limit : '');

            if ($paginate === null) {
                if ($only_active) {
                    return static::raw('SELECT * FROM `@THIS` WHERE active = 1 ORDER BY id ASC ' . $limit);
                } else {
                    return static::raw('SELECT * FROM `@THIS` ORDER BY id ASC ' . $limit);
                }
            } else {
                if ($only_active) {
                    return static::raw('SELECT * FROM `@THIS` WHERE active = 1 AND id >= ? ORDER BY id ASC ' . $limit, [$paginate]);
                } else {
                    return static::raw('SELECT * FROM `@THIS` WHERE id >= ? ORDER BY id ASC ' . $limit, [$paginate]);
                }
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $id
     * @return string
     * @throws \Exception
     */
    public static function getNameById($id)
    {
        try {
            return static::raw('SELECT * FROM `@THIS` WHERE id = ? LIMIT 1', [$id])->first()?->get('name');
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
            return static::raw('SELECT COUNT(*) as count FROM `@THIS`')->first()->get('count');
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $id
     * @return mixed
     * @throws \Exception
     */
    public static function getLocationById($id)
    {
        try {
            return static::raw('SELECT * FROM `@THIS` WHERE id = ?', [$id])->first();
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $name
     * @return void
     * @throws \Exception
     */
    public static function addLocation($name)
    {
        try {
            static::raw('INSERT INTO `@THIS` (name) VALUES(?)', [
                $name
            ]);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $id
     * @param $name
     * @param $active
     * @return void
     * @throws \Exception
     */
    public static function editLocation($id, $name, $active)
    {
        try {
            static::raw('UPDATE `@THIS` SET name = ?, active = ? WHERE id = ?', [
                $name, $active, $id
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
    public static function setPhoto($id)
    {
        try {
            if ((!isset($_FILES['photo'])) || ($_FILES['photo']['error'] !== UPLOAD_ERR_OK)) {
                throw new \Exception('No image provided');
            }

            static::clearPhoto($id);

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

            static::raw('UPDATE `@THIS` SET icon = ? WHERE id = ?', [$fullFileName, $id]);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $id
     * @return void
     * @throws \Exception
     */
    public static function clearPhoto($id)
    {
        try {
            $item = static::raw('SELECT * FROM `@THIS` WHERE id = ?', [$id])->first();
            if (!$item) {
                throw new \Exception('Item not found: ' . $id);
            }

            if ($item->get('icon')) {
                $thumb_photo = $item->get('icon');
                $full_photo = str_replace('_thumb', '', $item->get('icon'));

                if (file_exists(public_path() . '/img/' . $thumb_photo)) {
                    unlink(public_path() . '/img/' . $thumb_photo);
                }

                if (file_exists(public_path() . '/img/' . $full_photo)) {
                    unlink(public_path() . '/img/' . $full_photo);
                }

                static::raw('UPDATE `@THIS` SET icon = NULL WHERE id = ?', [$id]);
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $id
     * @param $notes
     * @return void
     * @throws \Exception
     */
    public static function saveNotes($id, $notes)
    {
        try {
            static::raw('UPDATE `@THIS` SET notes = ? WHERE id = ?', [
                $notes, $id
            ]);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $id
     * @param $target
     * @return void
     * @throws \Exception
     */
    public static function removeLocation($id, $target)
    {
        try {
            if ((static::getCount() <= 1) && (PlantsModel::getPlantCount($id))) {
                throw new \Exception(__('app.error_room_not_empty'));
            }

            PlantsModel::migratePlants($id, $target);

            static::clearPhoto($id);

            static::raw('DELETE FROM `@THIS` WHERE id = ?', [$id]);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $id
     * @return bool
     * @throws \Exception
     */
    public static function isActive($id)
    {
        try {
            $data = static::raw('SELECT * FROM `@THIS` WHERE id = ? AND active = 1', [$id])->first();
            return $data !== null;
        } catch (\Exception $e) {
            throw $e;
        }
    }
}