<?php

/*
    Asatru PHP - commands configuration file

    Add here all your custom commands to be used with the asatru CLI tool

    Schema:
        [<command>, <description>, <handler>]
    Example:
        ['test:cmd', 'This is a test command', 'TestCommand']
    Explanation:
        Will call non-static TestCommand::handle($args) in app/commands/TestCommand.php
*/

return [
    ['product:version', 'Show current product version', 'VersionCommand'],
    ['migrate:upgrade', 'Perform upgrade from last version to current version', 'MigrationUpgrade'],
    ['migrate:specific', 'Perform version specific migration upgrade', 'MigrationSpecific'],
    ['calendar:classes', 'Add default calendar classes', 'CalendarClsCommand'],
    ['plants:attributes', 'Add default plant attributes', 'AttributesCommand'],
    ['cache:clear', 'Clear the entire cache', 'CacheClearCommand'],
    ['attributes:sort-order', 'Add sort_order field to existing attribute schemas', 'AddSortOrderCommand']
];
