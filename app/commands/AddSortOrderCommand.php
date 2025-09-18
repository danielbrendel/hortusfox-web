<?php

/**
 * Command to add sort_order field to existing CustAttrSchemaModel tables
 */
class AddSortOrderCommand implements Asatru\Commands\Command {
    /**
     * Command handler method
     * 
     * @param $args
     * @return void
     */
    public function handle($args)
    {
        try {
            echo "Adding sort_order field to CustAttrSchemaModel table...\n";
            
            // Check if sort_order column already exists
            $columnExists = CustAttrSchemaModel::raw("SHOW COLUMNS FROM `@THIS` LIKE 'sort_order'")->first();
            
            if ($columnExists) {
                echo "sort_order column already exists. Skipping...\n";
                return;
            }
            
            // Add the sort_order column
            CustAttrSchemaModel::raw("ALTER TABLE `@THIS` ADD COLUMN `sort_order` INT NOT NULL DEFAULT 0 AFTER `active`");
            echo "Added sort_order column.\n";
            
            // Update existing records with sequential sort_order values
            $records = CustAttrSchemaModel::raw("SELECT id FROM `@THIS` ORDER BY id ASC");
            
            $index = 0;
            foreach ($records as $record) {
                CustAttrSchemaModel::raw("UPDATE `@THIS` SET sort_order = ? WHERE id = ?", [$index, $record->get('id')]);
                $index++;
            }
            
            echo "Updated " . $index . " existing records with sort_order values.\n";
            echo "Done!\n";
            
        } catch (\Exception $e) {
            echo "Error: " . $e->getMessage() . "\n";
        }
    }
}
