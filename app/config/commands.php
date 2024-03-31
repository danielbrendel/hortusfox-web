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
    ['migrate:upgrade', 'Perform version specific migration upgrades', 'MigrationUpgrade'],
    ['product:version', 'Show current product version', 'VersionCommand']
];
