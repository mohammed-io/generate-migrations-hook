<?php

namespace MohammedIO\Handlers;

use TCG\Voyager\Events\TableUpdated;

class TableUpdatedEventHandler
{
    use HandlerTrait;

    public $prefix = 'Updated';

    public function handle(TableUpdated $event)
    {
        $data = $event->name;

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
        return "DatabaseUpdater::update(
            $content
        );";
    }
}