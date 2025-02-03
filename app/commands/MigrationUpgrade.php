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
    public function upgradeTo4dot1()
    {
        try {
            ApiModel::raw('RENAME TABLE `apitable` TO `@THIS`');
        } catch (\Exception $e) {
            echo $e->getMessage() . PHP_EOL;
        }

        try {
            CalendarClassModel::raw('RENAME TABLE `calendarclasses` TO `@THIS`');
        } catch (\Exception $e) {
            echo $e->getMessage() . PHP_EOL;
        }

        try {
            CalendarInformerModel::raw('RENAME TABLE `calendarinformer` TO `@THIS`');
        } catch (\Exception $e) {
            echo $e->getMessage() . PHP_EOL;
        }

        try {
            CalendarModel::raw('RENAME TABLE `calendar` TO `@THIS`');
        } catch (\Exception $e) {
            echo $e->getMessage() . PHP_EOL;
        }

        try {
            ChatMsgModel::raw('RENAME TABLE `chatmsg` TO `@THIS`');
        } catch (\Exception $e) {
            echo $e->getMessage() . PHP_EOL;
        }

        try {
            ChatViewModel::raw('RENAME TABLE `chatview` TO `@THIS`');
        } catch (\Exception $e) {
            echo $e->getMessage() . PHP_EOL;
        }

        try {
            CustAttrSchemaModel::raw('RENAME TABLE `custattrschema` TO `@THIS`');
        } catch (\Exception $e) {
            echo $e->getMessage() . PHP_EOL;
        }

        try {
            CustBulkCmdModel::raw('RENAME TABLE `custbulkcmd` TO `@THIS`');
        } catch (\Exception $e) {
            echo $e->getMessage() . PHP_EOL;
        }

        try {
            CustPlantAttrModel::raw('RENAME TABLE `custplantattr` TO `@THIS`');
        } catch (\Exception $e) {
            echo $e->getMessage() . PHP_EOL;
        }

        try {
            InventoryModel::raw('RENAME TABLE `inventory` TO `@THIS`');
        } catch (\Exception $e) {
            echo $e->getMessage() . PHP_EOL;
        }
    }

    /**
     * @return void
     */
    public function upgradeTo4dot0()
    {
        PlantsModel::raw('ALTER TABLE `' . PlantsModel::tableName() . '` ADD COLUMN IF NOT EXISTS last_photo_date DATETIME NULL');

        InventoryModel::raw('ALTER TABLE `' . InventoryModel::tableName() . '` ADD COLUMN IF NOT EXISTS tags VARCHAR(512) NULL');
    }

    /**
     * @return void
     */
    public function upgradeTo3dot9()
    {
    }

    /**
     * @return void
     */
    public function upgradeTo3dot8()
    {
    }

    /**
     * @return void
     */
    public function upgradeTo3dot7()
    {
        AppModel::raw('ALTER TABLE `' . AppModel::tableName() . '` ADD COLUMN IF NOT EXISTS tasks_enable BOOLEAN NOT NULL DEFAULT 1');
        AppModel::raw('ALTER TABLE `' . AppModel::tableName() . '` ADD COLUMN IF NOT EXISTS inventory_enable BOOLEAN NOT NULL DEFAULT 1');
        AppModel::raw('ALTER TABLE `' . AppModel::tableName() . '` ADD COLUMN IF NOT EXISTS calendar_enable BOOLEAN NOT NULL DEFAULT 1');
        AppModel::raw('ALTER TABLE `' . AppModel::tableName() . '` ADD COLUMN IF NOT EXISTS custom_head_code TEXT NULL DEFAULT \'\'');
        AppModel::raw('ALTER TABLE `' . AppModel::tableName() . '` ADD COLUMN IF NOT EXISTS plantrec_enable BOOLEAN NOT NULL DEFAULT 0');
        AppModel::raw('ALTER TABLE `' . AppModel::tableName() . '` ADD COLUMN IF NOT EXISTS plantrec_apikey VARCHAR(512) NULL');
    }

    /**
     * @return void
     */
    public function upgradeTo3dot6()
    {
    }

    /**
     * @return void
     */
    public function upgradeTo3dot5()
    {
        AppModel::raw('ALTER TABLE `' . AppModel::tableName() . '` ADD COLUMN IF NOT EXISTS auth_proxy_enable BOOLEAN NOT NULL DEFAULT 0');
        AppModel::raw('ALTER TABLE `' . AppModel::tableName() . '` ADD COLUMN IF NOT EXISTS auth_proxy_header_email VARCHAR(512) NULL');
        AppModel::raw('ALTER TABLE `' . AppModel::tableName() . '` ADD COLUMN IF NOT EXISTS auth_proxy_header_username VARCHAR(512) NULL');
        AppModel::raw('ALTER TABLE `' . AppModel::tableName() . '` ADD COLUMN IF NOT EXISTS auth_proxy_sign_up BOOLEAN NOT NULL DEFAULT 0');
        AppModel::raw('ALTER TABLE `' . AppModel::tableName() . '` ADD COLUMN IF NOT EXISTS auth_proxy_whitelist TEXT NULL');
        AppModel::raw('ALTER TABLE `' . AppModel::tableName() . '` ADD COLUMN IF NOT EXISTS auth_proxy_hide_logout BOOLEAN NOT NULL DEFAULT 0');
        AppModel::raw('ALTER TABLE `' . AppModel::tableName() . '` ADD COLUMN IF NOT EXISTS custom_media_share_host VARCHAR(1024) NULL');
    }

    /**
     * @return void
     */
    public function upgradeTo3dot4()
    {
        AppModel::raw('ALTER TABLE `' . AppModel::tableName() . '` ADD COLUMN IF NOT EXISTS auto_backup BOOLEAN NOT NULL DEFAULT 0');
        AppModel::raw('ALTER TABLE `' . AppModel::tableName() . '` ADD COLUMN IF NOT EXISTS backup_path VARCHAR(1024) NULL');
    }

    /**
     * @return void
     */
    public function upgradeTo3dot3()
    {
        AppModel::raw('ALTER TABLE `' . AppModel::tableName() . '` ADD COLUMN IF NOT EXISTS mail_rp_address VARCHAR(512) NULL');
    }

    /**
     * @return void
     */
    public function upgradeTo3dot2()
    {
        AppModel::raw('ALTER TABLE `' . AppModel::tableName() . '` ADD COLUMN IF NOT EXISTS allow_custom_attributes BOOLEAN NOT NULL DEFAULT 0');

        AppModel::raw('ALTER TABLE `' . AppModel::tableName() . '` ADD COLUMN IF NOT EXISTS system_message_plant_log BOOLEAN NOT NULL DEFAULT 1');

        ChatMsgModel::raw('ALTER TABLE `' . ChatMsgModel::tableName() . '` RENAME COLUMN IF EXISTS system TO sysmsg');
    }

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

            echo "Upgrading to \033[94m{$version}\033[39m...\n";

            $method = 'upgradeTo' . str_replace('.', 'dot', $version);

            if (method_exists($this, $method)) {
                $this->$method();

                echo "\033[32mDone!\033[39m\n";
            } else {
                echo "\033[93mNothing to migrate or upgrade.\033[39m\n";
            }
        } catch (\Exception $e) {
            echo "\033[31mOperation failed: {$e->getMessage()}\033[39m\n";
        }
    }
}
    