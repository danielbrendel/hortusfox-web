<?php

/*
    Asatru PHP - Command handler
*/

/**
 * Command handler class
 */
class MigrationUpgrade implements Asatru\Commands\Command {
    /**
     * @return void
     */
    public function upgradeTo3dot1()
    {
        AppModel::raw('ALTER TABLE `' . AppModel::tableName() . '` ADD COLUMN IF NOT EXISTS timezone VARCHAR(512) NULL');

        PlantsModel::raw('ALTER TABLE `' . PlantsModel::tableName() . '` ADD COLUMN IF NOT EXISTS annual BOOLEAN NULL');

        UserModel::raw('ALTER TABLE `' . UserModel::tableName() . '` ADD COLUMN IF NOT EXISTS show_plant_id BOOLEAN NOT NULL DEFAULT 0');
    }

    /**
     * @return void
     */
    public function upgradeTo3dot0()
    {
        PlantsModel::raw('ALTER TABLE `' . PlantsModel::tableName() . '` ADD COLUMN IF NOT EXISTS clone_num INT NULL');

        InventoryModel::raw('ALTER TABLE `' . InventoryModel::tableName() . '` ADD COLUMN IF NOT EXISTS location VARCHAR(512) NULL');

        AppModel::raw('ALTER TABLE `' . AppModel::tableName() . '` ADD COLUMN IF NOT EXISTS owm_enable BOOLEAN NOT NULL DEFAULT 0');
        AppModel::raw('ALTER TABLE `' . AppModel::tableName() . '` ADD COLUMN IF NOT EXISTS owm_api_key VARCHAR(512) NULL');
        AppModel::raw('ALTER TABLE `' . AppModel::tableName() . '` ADD COLUMN IF NOT EXISTS owm_latitude DECIMAL(10, 8) NULL');
        AppModel::raw('ALTER TABLE `' . AppModel::tableName() . '` ADD COLUMN IF NOT EXISTS owm_longitude DECIMAL(11, 8) NULL');
        AppModel::raw('ALTER TABLE `' . AppModel::tableName() . '` ADD COLUMN IF NOT EXISTS owm_unittype VARCHAR(512) NOT NULL DEFAULT \'default\'');
        AppModel::raw('ALTER TABLE `' . AppModel::tableName() . '` ADD COLUMN IF NOT EXISTS owm_cache INT NOT NULL DEFAULT 300');
    }

    /**
     * @return void
     */
    public function upgradeTo2dot5()
    {
        PlantsModel::raw('ALTER TABLE `' . PlantsModel::tableName() . '` ADD COLUMN IF NOT EXISTS last_fertilised DATETIME NULL');

        UserModel::raw('ALTER TABLE `' . UserModel::tableName() . '` ADD COLUMN IF NOT EXISTS notify_calendar_reminder BOOLEAN NOT NULL DEFAULT 1');
        UserModel::raw('ALTER TABLE `' . UserModel::tableName() . '` ADD COLUMN IF NOT EXISTS show_calendar_view BOOLEAN NOT NULL DEFAULT 1');
    }

    /**
     * @return void
     */
    public function upgradeTo2dot4()
    {
        UserModel::raw('ALTER TABLE `' . UserModel::tableName() . '` DROP COLUMN IF EXISTS session');
        UserModel::raw('ALTER TABLE `' . UserModel::tableName() . '` DROP COLUMN IF EXISTS status');

        PlantsModel::raw('ALTER TABLE `' . PlantsModel::tableName() . '` MODIFY COLUMN perennial BOOLEAN NULL');
        PlantsModel::raw('ALTER TABLE `' . PlantsModel::tableName() . '` MODIFY COLUMN humidity INT NULL');
        PlantsModel::raw('ALTER TABLE `' . PlantsModel::tableName() . '` MODIFY COLUMN light_level VARCHAR(512) NULL');
    }

    /**
     * Command handler method
     * 
     * @param $args
     * @return void
     */
    public function handle($args)
    {
        try {
            $version = config('version');

            if (count($args) > 0) {
                $version = $args->get(0)->getValue();
            }

            echo "Upgrading to \033[93m{$version}\033[39m...\n";

            $method = 'upgradeTo' . str_replace('.', 'dot', $version);

            if (!method_exists($this, $method)) {
                throw new \Exception('Upgrade method not found for the specified version.');
            }

            $this->$method();

            echo "\033[32mDone!\033[39m\n";
        } catch (\Exception $e) {
            echo "\033[31mOperation failed: {$e->getMessage()}\033[39m\n";
        }
    }
}
    