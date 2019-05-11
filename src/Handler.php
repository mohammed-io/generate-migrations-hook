<?php

namespace MohammedIO;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use TCG\Voyager\Events\TableAdded;
use TCG\Voyager\Events\TableDeleted;
use TCG\Voyager\Events\TableUpdated;

class Handler
{
    /**
     * @var Utilities
     */
    private $utilities;
    /**
     * @var TokenMaker
     */
    private $tokenMaker;

    public function __construct(Utilities $utilities, TokenMaker $tokenMaker)
    {
        $this->utilities = $utilities;
        $this->tokenMaker = $tokenMaker;
    }

    public function updateMigrationTable($migrationName)
    {
        $lastBatch = optional(DB::table('migrations')->orderByDesc('id')->first())->batch ?? -1;

        DB::table('migrations')->insert([
            'migration' => $migrationName,
            'batch' => $lastBatch + 1
        ]);
    }

    public function makeUpdateMigration(array $data, $title)
    {
        $name = $title;
        $uniqueName = $this->utilities->generateMaybeUniqueClassName($name);

        $fileContent = $this->utilities->getFileContent([
            'upContent' => $this->utilities->generateContentWithDatabaseUpdater(
                $this->tokenMaker->arrayToArrayToken($data, 7)
            ),
            'name' => ucfirst(Str::camel($uniqueName))
        ]);

        $fileName = $this->utilities->generateFilename($uniqueName);

        $this->updateMigrationTable($fileName);

        $filePath = base_path("/database/migrations/$fileName.php");

        $this->utilities->writeFile($filePath, $fileContent);
    }

    public function generateUpdatedTitleFromTableName($tableName)
    {
        return "Updated $tableName table with voyager";
    }

    public function handleTableUpdated(TableUpdated $event)
    {
        $data = $event->name;

        $this->makeUpdateMigration($data,
            $this->generateUpdatedTitleFromTableName($data['name'])
        );
    }

    public function makeCreateMigration(array $data, $title)
    {
        $name = $title;
        $uniqueName = $this->utilities->generateMaybeUniqueClassName($name);

        $fileContent = $this->utilities->getFileContent([
            'upContent' => $this->utilities->generateContentWithSchemaManager(
                $this->tokenMaker->arrayToArrayToken($data, 7)
            ),
            'name' => ucfirst(Str::camel($uniqueName))
        ]);

        $fileName = $this->utilities->generateFilename($uniqueName);

        $this->updateMigrationTable($fileName);

        $filePath = base_path("/database/migrations/$fileName.php");

        $this->utilities->writeFile($filePath, $fileContent);
    }

    public function generateCreatedTitleFromTableName($tableName)
    {
        return "Created $tableName table with voyager";
    }

    public function handleTableAdded(TableAdded $event)
    {
        $data = $event->table->toArray();
        $this->makeCreateMigration($data,
            $this->generateCreatedTitleFromTableName($data['name'])
        );
    }

    public function generateDeletedTitleFromTableName($tableName)
    {
        return "Deleted $tableName table with voyager";
    }

    public function makeDeleteMigration($tableName, $title)
    {
        $name = $title;
        $uniqueName = $this->utilities->generateMaybeUniqueClassName($name);

        $fileContent = $this->utilities->getFileContent([
            'upContent' => $this->utilities->generateContentWithDropStatement(
                $tableName
            ),
            'name' => ucfirst(Str::camel($uniqueName))
        ]);

        $fileName = $this->utilities->generateFilename($uniqueName);

        $this->updateMigrationTable($fileName);

        $filePath = base_path("/database/migrations/$fileName.php");

        $this->utilities->writeFile($filePath, $fileContent);
    }

    public function handleTableDeleted(TableDeleted $event)
    {
        $this->makeDeleteMigration($event->name,
            $this->generateDeletedTitleFromTableName($event->name)
        );
    }
}