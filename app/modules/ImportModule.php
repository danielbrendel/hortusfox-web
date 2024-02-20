<?php

/**
 * This class represents your module
 */
class ImportModule {
    /**
     * @param $options
     * @return void
     * @throws \Exception
     */
    public static function start($options = [])
    {
        try {
            if ((!isset($_FILES['import'])) || ($_FILES['import']['error'] !== UPLOAD_ERR_OK) || (strpos($_FILES['import']['type'], 'zip') === false)) {
                throw new \Exception('Failed to upload file or invalid file uploaded');
            }

            $import_file = 'hf_import_' . date('Y-m-d_H-i-s');

            move_uploaded_file($_FILES['import']['tmp_name'], public_path() . '/backup/' . $import_file . '.zip');

            $zip = new ZipArchive();

            if ($zip->open(public_path() . '/backup/' . $import_file . '.zip')) {
                $zip->extractTo(public_path() . '/backup/' . $import_file);
                $zip->close();

                if ((isset($options['plants'])) && ($options['plants'])) {
                    static::importPlants(public_path() . '/backup/' . $import_file . '/plants');
                }

                if ((isset($options['gallery'])) && ($options['gallery'])) {
                    static::importGallery(public_path() . '/backup/' . $import_file . '/gallery');
                }

                if ((isset($options['tasks'])) && ($options['tasks'])) {
                    static::importTasks(public_path() . '/backup/' . $import_file . '/tasks');
                }

                if ((isset($options['inventory'])) && ($options['inventory'])) {
                    static::importInventory(public_path() . '/backup/' . $import_file . '/inventory');
                }

                UtilsModule::clearFolder(public_path() . '/backup/' . $import_file);
            }

            unlink(public_path() . '/backup/' . $import_file . '.zip');
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $path
     * @return void
     * @throws \Exception
     */
    public static function importPlants($path)
    {
        try {
            $plants = json_decode(file_get_contents($path . '/data.json'));
            if ($plants) {
                foreach ($plants as $plant) {
                    PlantsModel::raw('INSERT INTO `' . PlantsModel::tableName() . '` (name, scientific_name, knowledge_link, tags, location, photo, last_watered, last_repotted, perennial, cutting_month, date_of_purchase, humidity, light_level, health_state, notes, history, history_date, last_edited_user, last_edited_date, created_at) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)', [
                        $plant->name,
                        $plant->scientific_name,
                        $plant->knowledge_link,
                        $plant->tags,
                        $plant->location,
                        $plant->photo,
                        $plant->last_watered,
                        $plant->last_repotted,
                        $plant->perennial,
                        $plant->cutting_month,
                        $plant->date_of_purchase,
                        $plant->humidity,
                        $plant->light_level,
                        $plant->health_state,
                        $plant->notes,
                        $plant->history,
                        $plant->history_date,
                        $plant->last_edited_user,
                        $plant->last_edited_date,
                        $plant->created_at
                    ]);

                    if ((!file_exists(public_path() . '/img/' . $plant->photo)) && (file_exists($path . '/img/' . $plant->photo))) {
                        copy($path . '/img/' . $plant->photo, public_path() . '/img/' . $plant->photo);
                    }
                }
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $path
     * @return void
     * @throws \Exception
     */
    public static function importGallery($path)
    {
        try {
            $gallery_items = json_decode(file_get_contents($path . '/data.json'));
            if ($gallery_items) {
                foreach ($gallery_items as $gallery_item) {
                    PlantPhotoModel::raw('INSERT INTO `' . PlantPhotoModel::tableName() . '` (plant, author, thumb, original, label, created_at) VALUES(?, ?, ?, ?, ?, ?)', [
                        $gallery_item->plant,
                        $gallery_item->author,
                        $gallery_item->thumb,
                        $gallery_item->original,
                        $gallery_item->label,
                        $gallery_item->created_at
                    ]);

                    if ((!file_exists(public_path() . '/img/' . $gallery_item->thumb)) && (file_exists($path . '/img/' . $gallery_item->thumb))) {
                        copy($path . '/img/' . $gallery_item->thumb, public_path() . '/img/' . $gallery_item->thumb);
                    }

                    if ((!file_exists(public_path() . '/img/' . $gallery_item->original)) && (file_exists($path . '/img/' . $gallery_item->original))) {
                        copy($path . '/img/' . $gallery_item->original, public_path() . '/img/' . $gallery_item->original);
                    }
                }
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $path
     * @return void
     * @throws \Exception
     */
    public static function importTasks($path)
    {
        try {
            $tasks = json_decode(file_get_contents($path . '/data.json'));
            if ($tasks) {
                foreach ($tasks as $task) {
                    TasksModel::raw('INSERT INTO `' . TasksModel::tableName() . '` (title, description, due_date, done, created_at) VALUES(?, ?, ?, ?, ?)', [
                        $task->title,
                        $task->description,
                        $task->due_date,
                        $task->done,
                        $task->created_at
                    ]);
                }
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $path
     * @return void
     * @throws \Exception
     */
    public static function importInventory($path)
    {
        try {
            $inventory_items = json_decode(file_get_contents($path . '/data.json'));
            if ($inventory_items) {
                foreach ($inventory_items as $inventory_item) {
                    InventoryModel::raw('INSERT INTO `' . InventoryModel::tableName() . '` (name, group_ident, description, photo, amount, last_edited_user, last_edited_date, created_at) VALUES(?, ?, ?, ?, ?, ?, ?, ?)', [
                        $inventory_item->name,
                        $inventory_item->group_ident,
                        $inventory_item->description,
                        $inventory_item->photo,
                        $inventory_item->amount,
                        $inventory_item->last_edited_user,
                        $inventory_item->last_edited_date,
                        $inventory_item->created_at
                    ]);

                    if ((!file_exists(public_path() . '/img/' . $inventory_item->photo)) && (file_exists($path . '/img/' . $inventory_item->photo))) {
                        copy($path . '/img/' . $inventory_item->photo, public_path() . '/img/' . $inventory_item->photo);
                    }
                }
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
