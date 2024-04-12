<?php

/**
 * This class represents your module
 */
class BackupModule {
    /**
     * @param $options
     * @return string|null
     * @throws \Exception
     */
    public static function start($options = [])
    {
        try {
            $backup_file = 'hf_backup_' . date('Y-m-d_H-i-s') . '.zip';

            $zip = new ZipArchive();

            $cleanup_files = [];
            
            if ($zip->open(public_path() . '/backup/' . $backup_file, ZIPARCHIVE::CREATE | ZipArchive::OVERWRITE)) {
                if ((isset($options['locations'])) && ($options['locations'])) {
                    $cleanup_files[] = static::backupLocations($zip);
                }

                if ((isset($options['plants'])) && ($options['plants'])) {
                    $cleanup_files[] = static::backupPlants($zip);
                }

                if ((isset($options['gallery'])) && ($options['gallery'])) {
                    $cleanup_files[] = static::backupGallery($zip);
                }

                if ((isset($options['tasks'])) && ($options['tasks'])) {
                    $cleanup_files[] = static::backupTasks($zip);
                }

                if ((isset($options['inventory'])) && ($options['inventory'])) {
                    $cleanup_files[] = static::backupInventory($zip);
                }

                if ((isset($options['calendar'])) && ($options['calendar'])) {
                    $cleanup_files[] = static::backupCalendar($zip);
                    $cleanup_files[] = static::backupCalClasses($zip);
                }

                $zip->close();

                foreach ($cleanup_files as $cleanup_file) {
                    if (file_exists($cleanup_file)) {
                        unlink($cleanup_file);
                    }
                }

                return $backup_file;
            }

            return null;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $zip
     * @return string
     * @throws \Exception
     */
    private static function backupLocations(ZipArchive $zip)
    {
        try {
            $locations = LocationsModel::raw('SELECT * FROM `locations`');

            $zip->addEmptyDir('locations');

            file_put_contents(public_path() . '/backup/_locations.json', json_encode($locations->asArray()));
            $zip->addFile(public_path() . '/backup/_locations.json', 'locations/data.json');

            return public_path() . '/backup/_locations.json';
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $zip
     * @return string
     * @throws \Exception
     */
    private static function backupPlants(ZipArchive $zip)
    {
        try {
            $plants = PlantsModel::raw('SELECT * FROM `plants`');

            $zip->addEmptyDir('plants');
            $zip->addEmptyDir('plants/img');

            foreach ($plants as $plant) {
                if (file_exists(public_path() . '/img/' . $plant->get('photo'))) {
                    $zip->addFile(public_path() . '/img/' . $plant->get('photo'), 'plants/img/' . $plant->get('photo'));
                }
            }

            file_put_contents(public_path() . '/backup/_plants.json', json_encode($plants->asArray()));
            $zip->addFile(public_path() . '/backup/_plants.json', 'plants/data.json');

            return public_path() . '/backup/_plants.json';
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $zip
     * @return string
     * @throws \Exception
     */
    public static function backupGallery(ZipArchive $zip)
    {
        try {
            $photos = PlantPhotoModel::raw('SELECT * FROM `plantphotos`');

            $zip->addEmptyDir('gallery');
            $zip->addEmptyDir('gallery/img');

            foreach ($photos as $photo) {
                if (file_exists(public_path() . '/img/' . $photo->get('thumb'))) {
                    $zip->addFile(public_path() . '/img/' . $photo->get('thumb'), 'gallery/img/' . $photo->get('thumb'));
                }

                if (file_exists(public_path() . '/img/' . $photo->get('original'))) {
                    $zip->addFile(public_path() . '/img/' . $photo->get('original'), 'gallery/img/' . $photo->get('original'));
                }
            }

            file_put_contents(public_path() . '/backup/_photos.json', json_encode($photos->asArray()));
            $zip->addFile(public_path() . '/backup/_photos.json', 'gallery/data.json');

            return public_path() . '/backup/_photos.json';
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $zip
     * @return string
     * @throws \Exception
     */
    public static function backupTasks(ZipArchive $zip)
    {
        try {
            $tasks = TasksModel::raw('SELECT * FROM `tasks`');

            $zip->addEmptyDir('tasks');

            file_put_contents(public_path() . '/backup/_tasks.json', json_encode($tasks->asArray()));
            $zip->addFile(public_path() . '/backup/_tasks.json', 'tasks/data.json');

            return public_path() . '/backup/_tasks.json';
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $zip
     * @return string
     * @throws \Exception
     */
    public static function backupInventory(ZipArchive $zip)
    {
        try {
            $inventory = InventoryModel::raw('SELECT * FROM `inventory`');

            $zip->addEmptyDir('inventory');
            $zip->addEmptyDir('inventory/img');

            foreach ($inventory as $item) {
                if (($item->get('photo')) && (file_exists(public_path() . '/img/' . $item->get('photo')))) {
                    $zip->addFile(public_path() . '/img/' . $item->get('photo'), 'inventory/img/' . $item->get('photo'));
                }
            }

            file_put_contents(public_path() . '/backup/_inventory.json', json_encode($inventory->asArray()));
            $zip->addFile(public_path() . '/backup/_inventory.json', 'inventory/data.json');

            return public_path() . '/backup/_inventory.json';
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $zip
     * @return string
     * @throws \Exception
     */
    public static function backupCalendar(ZipArchive $zip)
    {
        try {
            $calendar_items = CalendarModel::raw('SELECT * FROM `calendar`');

            $zip->addEmptyDir('calendar');

            file_put_contents(public_path() . '/backup/_calendar.json', json_encode($calendar_items->asArray()));
            $zip->addFile(public_path() . '/backup/_calendar.json', 'calendar/data.json');

            return public_path() . '/backup/_calendar.json';
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $zip
     * @return string
     * @throws \Exception
     */
    public static function backupCalClasses(ZipArchive $zip)
    {
        try {
            $calendar_class_items = CalendarClassModel::raw('SELECT * FROM `calendarclasses`');

            $zip->addEmptyDir('calcls');

            file_put_contents(public_path() . '/backup/_calcls.json', json_encode($calendar_class_items->asArray()));
            $zip->addFile(public_path() . '/backup/_calcls.json', 'calcls/data.json');

            return public_path() . '/backup/_calcls.json';
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
