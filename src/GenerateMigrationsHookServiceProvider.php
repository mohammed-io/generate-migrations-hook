<?php

namespace MohammedIO;

use Illuminate\Support\Str;
use Illuminate\Events\Dispatcher;
use Illuminate\Support\ServiceProvider;
use MohammedIO\Handlers\TableAddedEventHandler;
use MohammedIO\Handlers\TableDeletedEventHandler;
use MohammedIO\Handlers\TableUpdatedEventHandler;
use TCG\Voyager\Events\TableAdded;
use TCG\Voyager\Events\TableDeleted;
use TCG\Voyager\Events\TableUpdated;

class GenerateMigrationsHookServiceProvider extends ServiceProvider
{
    public function __construct($app)
    {
        parent::__construct($app);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        app(Dispatcher::class)->listen(TableUpdated::class, TableUpdatedEventHandler::class);

        app(Dispatcher::class)->listen(TableAdded::class, TableAddedEventHandler::class);

        app(Dispatcher::class)->listen(TableDeleted::class, TableDeletedEventHandler::class);
    }
}
