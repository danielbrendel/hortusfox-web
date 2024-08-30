<?php

/**
 * Class ImportModule
 * 
 * Performs imports of backups
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

                if ((isset($options['locations'])) && ($options['locations'])) {
                    static::importLocations(public_path() . '/backup/' . $import_file . '/locations');
                }

                if ((isset($options['plants'])) && ($options['plants'])) {
                    static::importPlants(public_path() . '/backup/' . $import_file . '/plants');
                    static::importPlantLog(public_path() . '/backup/' . $import_file . '/plantlog');
                }

                if ((isset($options['gallery'])) && ($options['gallery'])) {
                    static::importGallery(public_path() . '/backup/' . $import_file . '/gallery');
                }

                if ((isset($options['tasks'])) && ($options['tasks'])) {
                    static::importTasks(public_path() . '/backup/' . $import_file . '/tasks');
                }

                if ((isset($options['inventory'])) && ($options['inventory'])) {
                    static::importInvGroups(public_path() . '/backup/' . $import_file . '/invgroups');
                    static::importInventory(public_path() . '/backup/' . $import_file . '/inventory');
                }

                if ((isset($options['calendar'])) && ($options['calendar'])) {
                    static::importCalendar(public_path() . '/backup/' . $import_file . '/calendar');
                    static::importCalClasses(public_path() . '/backup/' . $import_file . '/calcls');
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
    public static function importLocations($path)
    {
        try {
            if (!file_exists($path . '/data.json')) {
                return;
            }

            $locations = json_decode(file_get_contents($path . '/data.json'));
            if ($locations) {
                foreach ($locations as $location) {
                    LocationsModel::raw('INSERT IGNORE INTO `' . LocationsModel::tableName() . '` (id, name, icon, active, created_at) VALUES(?, ?, ?, ?, ?)', [
                        $location->id,
                        $location->name,
                        $location->icon,
                        $location->active,
                        $location->created_at
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
    public static function importPlants($path)
    {
        try {
            if (!file_exists($path . '/data.json')) {
                return;
            }

            $plants = json_decode(file_get_contents($path . '/data.json'));
            if ($plants) {
                foreach ($plants as $plant) {
                    PlantsModel::raw('INSERT IGNORE INTO `' . PlantsModel::tableName() . '` (id, name, scientific_name, knowledge_link, tags, location, photo, last_watered, last_repotted, perennial, cutting_month, date_of_purchase, humidity, light_level, health_state, notes, history, history_date, last_edited_user, last_edited_date, created_at) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)', [
                        $plant->id,
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
    public static function importPlantLog($path)
    {
        try {
            if (!file_exists($path . '/data.json')) {
                return;
            }

            $plantlog_entries = json_decode(file_get_contents($path . '/data.json'));
            if ($plantlog_entries) {
                foreach ($plantlog_entries as $plantlog_entry) {
                    PlantLogModel::raw('INSERT IGNORE INTO `' . PlantLogModel::tableName() . '` (plant, content, updated_at, created_at) VALUES(?, ?, ?, ?)', [
                        $plantlog_entry->plant,
                        $plantlog_entry->content,
                        $plantlog_entry->updated_at,
                        $plantlog_entry->created_at
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
    public static function importGallery($path)
    {
        try {
            if (!file_exists($path . '/data.json')) {
                return;
            }

            $gallery_items = json_decode(file_get_contents($path . '/data.json'));
            if ($gallery_items) {
                foreach ($gallery_items as $gallery_item) {
                    PlantPhotoModel::raw('INSERT IGNORE INTO `' . PlantPhotoModel::tableName() . '` (plant, author, thumb, original, label, created_at) VALUES(?, ?, ?, ?, ?, ?)', [
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
            if (!file_exists($path . '/data.json')) {
                return;
            }

            $tasks = json_decode(file_get_contents($path . '/data.json'));
            if ($tasks) {
                foreach ($tasks as $task) {
                    TasksModel::raw('INSERT IGNORE INTO `' . TasksModel::tableName() . '` (title, description, due_date, done, created_at) VALUES(?, ?, ?, ?, ?)', [
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
    public static function importInvGroups($path)
    {
        try {
            if (!file_exists($path . '/data.json')) {
                return;
            }

            $invgroup_items = json_decode(file_get_contents($path . '/data.json'));
            if ($invgroup_items) {
                foreach ($invgroup_items as $invgroup_item) {
                    InvGroupModel::raw('INSERT IGNORE INTO `' . InvGroupModel::tableName() . '` (id, token, label, created_at) VALUES(?, ?, ?, ?)', [
                        $invgroup_item->id,
                        $invgroup_item->token,
                        $invgroup_item->label,
                        $invgroup_item->created_at
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
            if (!file_exists($path . '/data.json')) {
                return;
            }

            $inventory_items = json_decode(file_get_contents($path . '/data.json'));
            if ($inventory_items) {
                foreach ($inventory_items as $inventory_item) {
                    InventoryModel::raw('INSERT IGNORE INTO `' . InventoryModel::tableName() . '` (name, group_ident, description, photo, amount, last_edited_user, last_edited_date, created_at) VALUES(?, ?, ?, ?, ?, ?, ?, ?)', [
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

    /**
     * @param $path
     * @return void
     * @throws \Exception
     */
    public static function importCalendar($path)
    {
        try {
            if (!file_exists($path . '/data.json')) {
                return;
            }

            $calendar_items = json_decode(file_get_contents($path . '/data.json'));
            if ($calendar_items) {
                foreach ($calendar_items as $calendar_item) {
                    CalendarModel::raw('INSERT IGNORE INTO `' . CalendarModel::tableName() . '` (name, date_from, date_till, class_name, color_background, color_border, last_edited_user, last_edited_date, created_at) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?)', [
                        $calendar_item->name,
                        $calendar_item->date_from,
                        $calendar_item->date_till,
                        $calendar_item->class_name,
                        $calendar_item->color_background,
                        $calendar_item->color_border,
                        $calendar_item->last_edited_user,
                        $calendar_item->last_edited_date,
                        $calendar_item->created_at
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
    public static function importCalClasses($path)
    {
        try {
            if (!file_exists($path . '/data.json')) {
                return;
            }

            $calendar_class_items = json_decode(file_get_contents($path . '/data.json'));
            if ($calendar_class_items) {
                foreach ($calendar_class_items as $calendar_class_item) {
                    CalendarModel::raw('INSERT IGNORE INTO `' . CalendarClassModel::tableName() . '` (ident, name, color_background, color_border, created_at) VALUES(?, ?, ?, ?, ?)', [
                        $calendar_class_item->ident,
                        $calendar_class_item->name,
                        $calendar_class_item->color_background,
                        $calendar_class_item->color_border,
                        $calendar_class_item->created_at
                    ]);
                }
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
