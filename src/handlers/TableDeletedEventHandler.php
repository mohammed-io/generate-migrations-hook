<?php

namespace MohammedIO\Handlers;

use TCG\Voyager\Events\TableDeleted;

class TableDeletedEventHandler
{
    use HandlerTrait;

    public $prefix = 'Deleted';

    public function handle(TableDeleted $event)
    {
        $data = ['name' => $event->name];

        $this->upContent = $this->generateContent($event->name);

        $this->makeMigrationIfNeeded($data);
    }

    /**
     * @param string $tableName
     * @return string
     */
    public function generateContent(string $tableName)
    {
        return "Schema::dropIfExists('$tableName');";
    }
}