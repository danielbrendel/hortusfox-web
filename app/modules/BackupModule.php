<?php

/**
 * Class BackupModule
 * 
 * Performs exports of backups
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
                    $cleanup_files[] = static::backupLocationLog($zip);
                }

                if ((isset($options['plants'])) && ($options['plants'])) {
                    $cleanup_files[] = static::backupPlants($zip);
                    $cleanup_files[] = static::backupAttributeSchemata($zip);
                    $cleanup_files[] = static::backupPlantAttributes($zip);
                    $cleanup_files[] = static::backupPlantLog($zip);
                    $cleanup_files[] = static::backupCustBulkCmds($zip);
                }

                if ((isset($options['gallery'])) && ($options['gallery'])) {
                    $cleanup_files[] = static::backupGallery($zip);
                }

                if ((isset($options['tasks'])) && ($options['tasks'])) {
                    $cleanup_files[] = static::backupTasks($zip);
                }

                if ((isset($options['inventory'])) && ($options['inventory'])) {
                    $cleanup_files[] = static::backupInvGroups($zip);
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
            $locations = LocationsModel::raw('SELECT * FROM `@THIS`');

            $zip->addEmptyDir('locations');
            $zip->addEmptyDir('locations/img');

            foreach ($locations as $location) {
                if (file_exists(public_path() . '/img/' . $location->get('icon'))) {
                    $zip->addFile(public_path() . '/img/' . $location->get('icon'), 'locations/img/' . $location->get('icon'));
                }
            }

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
    private static function backupLocationLog(ZipArchive $zip)
    {
        try {
            $locationlog = LocationLogModel::raw('SELECT * FROM `@THIS`');

            $zip->addEmptyDir('locationlog');

            file_put_contents(public_path() . '/backup/_locationlog.json', json_encode($locationlog->asArray()));
            $zip->addFile(public_path() . '/backup/_locationlog.json', 'locationlog/data.json');

            return public_path() . '/backup/_locationlog.json';
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
            $plants = PlantsModel::raw('SELECT * FROM `@THIS`');

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
    private static function backupAttributeSchemata(ZipArchive $zip)
    {
        try {
            $attrschemata = CustAttrSchemaModel::raw('SELECT * FROM `@THIS`');

            $zip->addEmptyDir('attrschemata');

            file_put_contents(public_path() . '/backup/_attrschemata.json', json_encode($attrschemata->asArray()));
            $zip->addFile(public_path() . '/backup/_attrschemata.json', 'attrschemata/data.json');

            return public_path() . '/backup/_attrschemata.json';
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $zip
     * @return string
     * @throws \Exception
     */
    private static function backupPlantAttributes(ZipArchive $zip)
    {
        try {
            $plantattrs = CustPlantAttrModel::raw('SELECT * FROM `@THIS`');

            $zip->addEmptyDir('plantattrs');

            file_put_contents(public_path() . '/backup/_plantattrs.json', json_encode($plantattrs->asArray()));
            $zip->addFile(public_path() . '/backup/_plantattrs.json', 'plantattrs/data.json');

            return public_path() . '/backup/_plantattrs.json';
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $zip
     * @return string
     * @throws \Exception
     */
    private static function backupCustBulkCmds(ZipArchive $zip)
    {
        try {
            $bulkcmds = CustBulkCmdModel::raw('SELECT * FROM `@THIS`');

            $zip->addEmptyDir('bulkcmds');

            file_put_contents(public_path() . '/backup/_bulkcmds.json', json_encode($bulkcmds->asArray()));
            $zip->addFile(public_path() . '/backup/_bulkcmds.json', 'bulkcmds/data.json');

            return public_path() . '/backup/_bulkcmds.json';
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $zip
     * @return string
     * @throws \Exception
     */
    private static function backupPlantLog(ZipArchive $zip)
    {
        try {
            $plantlog = PlantLogModel::raw('SELECT * FROM `@THIS`');

            $zip->addEmptyDir('plantlog');

            file_put_contents(public_path() . '/backup/_plantlog.json', json_encode($plantlog->asArray()));
            $zip->addFile(public_path() . '/backup/_plantlog.json', 'plantlog/data.json');

            return public_path() . '/backup/_plantlog.json';
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
            $photos = PlantPhotoModel::raw('SELECT * FROM `@THIS`');

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
            $tasks = TasksModel::raw('SELECT * FROM `@THIS`');

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
    public static function backupInvGroups(ZipArchive $zip)
    {
        try {
            $invgroups = InvgroupModel::raw('SELECT * FROM `@THIS`');

            $zip->addEmptyDir('invgroups');

            file_put_contents(public_path() . '/backup/_invgroups.json', json_encode($invgroups->asArray()));
            $zip->addFile(public_path() . '/backup/_invgroups.json', 'invgroups/data.json');

            return public_path() . '/backup/_invgroups.json';
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
            $inventory = InventoryModel::raw('SELECT * FROM `@THIS`');

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
            $calendar_items = CalendarModel::raw('SELECT * FROM `@THIS`');

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
            $calendar_class_items = CalendarClassModel::raw('SELECT * FROM `@THIS`');

            $zip->addEmptyDir('calcls');

            file_put_contents(public_path() . '/backup/_calcls.json', json_encode($calendar_class_items->asArray()));
            $zip->addFile(public_path() . '/backup/_calcls.json', 'calcls/data.json');

            return public_path() . '/backup/_calcls.json';
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
