<?php

/**
 * Class PlantAttachmentModel
 * 
 * Manages attachments for plants
 */ 
class PlantAttachmentModel extends \Asatru\Database\Model {
    /**
     * @param $plantId
     * @param $label
     * @param $api
     * @return int
     * @throws \Exception
     */
    public static function upload($plantId, $label, $api = false)
    {
        try {
            $user = UserModel::getAuthUser();
            if ((!$user) && (!$api)) {
                throw new \Exception('Invalid user');
            }

            if ((!isset($_FILES['attachment'])) || ($_FILES['attachment']['error'] !== UPLOAD_ERR_OK)) {
                throw new \Exception('Errorneous file');
            }

            $file_ext = pathinfo($_FILES['attachment']['name'], PATHINFO_EXTENSION);
            $file_name = md5(random_bytes(55) . date('Y-m-d H:i:s'));

            move_uploaded_file($_FILES['attachment']['tmp_name'], public_path('/attachments/' . $file_name . '.' . $file_ext));

            static::raw('INSERT INTO `@THIS` (label, file, plant, author) VALUES(?, ?, ?, ?)', [
                $label, $file_name . '.' . $file_ext, $plantId, (($user) ? $user->get('id') : 0)
            ]);

            if (!$api) {
                LogModel::addLog($user->get('id'), $plantId, 'add_plant_attachment', $label, url('/plants/details/' . $plantId . '#plant-attachments-anchor'));

                if (app('system_message_plant_log')) {
                    PlantLogModel::addEntry($plantId, '[System] add_plant_attachment: ' . $label . ' = ' . $file_name . '.' . $file_ext);
                }
            }

            $recent = static::raw('SELECT * FROM `@THIS` ORDER BY id DESC LIMIT 1')->first();
            if ($recent) {
                return $recent->get('id');
            }

            return 0;
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
            $item = static::raw('SELECT * FROM `@THIS` WHERE id = ?', [$id])->first();
            $plant = PlantsModel::getDetails($item->get('plant'));

            static::raw('UPDATE `@THIS` SET label = ? WHERE id = ?', [$label, $id]);

            if ((app('system_message_plant_log')) && (!$api)) {
                PlantLogModel::addEntry($plant->get('id'), '[System] edit_plant_attachment: \'' . $item->get('label') . '\' to \'' . $label . '\'');
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $id
     * @param $ra
     * @return void
     * @throws \Exception
     */
    public static function removeAttachment($id, $ra = true)
    {
        try {
            $item = static::raw('SELECT * FROM `@THIS` WHERE id = ?', [$id])->first();
            if (!$item) {
                throw new \Exception('Plant attachment not found: ' . $id);
            }
            
            if (($ra) && (strlen($item->get('file')) > 0) && (file_exists(public_path() . '/attachments/' . $item->get('file')))) {
                unlink(public_path() . '/attachments/' . $item->get('file'));
            }

            static::raw('DELETE FROM `@THIS` WHERE id = ?', [$id]);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $plant
     * @param $paginate
     * @param $limit
     * @return mixed
     * @throws \Exception
     */
    public static function getForPlant($plant, $paginate = null, $limit = 10)
    {
        try {
            if ($paginate) {
                return static::raw('SELECT * FROM `@THIS` WHERE plant = ? AND id < ? ORDER BY id DESC LIMIT ' . $limit, [$plant, $paginate]);
            } else {
                return static::raw('SELECT * FROM `@THIS` WHERE plant = ? ORDER BY id DESC LIMIT ' . $limit, [$plant]);
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $source
     * @param $target
     * @return void
     * @throws \Exception
     */
    public static function cloneAttachments($source, $target)
    {
        try {
            $items = static::raw('SELECT * FROM `@THIS` WHERE plant = ?', [$source]);
            foreach ($items as $item) {
                static::raw('INSERT INTO `@THIS` (label, file, plant, author) VALUES(?, ?, ?, ?)', [
                    $item->get('label'), $item->get('file'), $target, $item->get('author')
                ]);
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $plant_id
     * @param $remove_files
     * @return void
     * @throws \Exception
     */
    public static function clearForPlant($plant_id, $remove_files = true)
    {
        try {
            $items = static::raw('SELECT * FROM `@THIS` WHERE plant = ?', [$plant_id]);
            foreach ($items as $item) {
                static::removeAttachment($item->get('id'), $remove_files);
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }
}