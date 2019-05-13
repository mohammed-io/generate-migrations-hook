<?php

namespace MohammedIO\Handlers;

use TCG\Voyager\Events\TableAdded;

class TableAddedEventHandler
{
    use HandlerTrait;

    public $prefix = 'Created';

    public function handle(TableAdded $event)
    {
        $data = $event->table->toArray();

        $this->upContent = $this->generateContent(
            $this->getTokenMaker()->arrayToArrayToken($data, 7)
        );

        $this->makeMigrationIfNeeded($data);
    }

    /**
     * @param string $content
     * @return string
     */
    public function generateContent(string $content)
    {
        return "SchemaManager::createTable(
            $content
        );";
    }
}