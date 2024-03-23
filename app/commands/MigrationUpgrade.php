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
    public function upgradeTo2dot5()
    {
        PlantsModel::raw('ALTER TABLE `' . PlantsModel::tableName() . '` ADD COLUMN last_fertilised DATETIME NULL');
    }

    /**
     * @return void
     */
    public function upgradeTo2dot4()
    {
        UserModel::raw('ALTER TABLE `' . UserModel::tableName() . '` DROP COLUMN session');
        UserModel::raw('ALTER TABLE `' . UserModel::tableName() . '` DROP COLUMN status');

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
    