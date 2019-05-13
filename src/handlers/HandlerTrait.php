<?php

namespace MohammedIO\Handlers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use MohammedIO\Templates\MigrationFileTemplate;
use MohammedIO\TokenMaker;
use MohammedIO\Utilities;
use TCG\Voyager\Events\TableUpdated;

/**
 * Trait HandlerTrait
 * @package MohammedIO\Handlers
 *
 * @property string $prefix
 * @method generateContent(string $content)
 */
trait HandlerTrait
{
    /**
     * @var string $upContent
     */
    public $upContent;

    /**
     * @return Utilities
     */
    public function getUtilities()
    {
        return app(Utilities::class);
    }

    /**
     * @return TokenMaker
     */
    public function getTokenMaker()
    {
        return app(TokenMaker::class);
    }

    /**
     * @return MigrationFileTemplate
     */
    public function getMigrationTemplate()
    {
        return app(MigrationFileTemplate::class);
    }

    public function updateMigrationTable($migrationName)
    {
        $lastBatch = optional(DB::table('migrations')->orderByDesc('id')->first())->batch ?? -1;

        DB::table('migrations')->insert([
            'migration' => $migrationName,
            'batch' => $lastBatch + 1
        ]);
    }

    public function makeMigrationIfNeeded(array $data)
    {
        $name = $this->generateTitleFromTableName($data['name']);
        $uniqueName = $this->getUtilities()->generateMaybeUniqueClassName($name);

        $upContentHash = md5($this->upContent);

        $fileContent = $this->getMigrationTemplate()->generate([
            'upContent' => $this->upContent,
            'upContentHash' => $upContentHash,
            'name' => ucfirst(Str::camel($uniqueName))
        ]);

        $fileName = $this->getUtilities()->generateFilename($uniqueName);

        $this->updateMigrationTable($fileName);

        $filePath = base_path("/database/migrations/$fileName.php");

        $this->getUtilities()->writeFile($filePath, $fileContent);
    }

    public function listMigrations()
    {
        return collect(File::files(base_path('/database/migrations')))->map(function ($fileInfo) {
            return $fileInfo->filename;
        });
    }

    public function generateTitleFromTableName($tableName)
    {
        return $this->prefix . " $tableName table with voyager";
    }
}