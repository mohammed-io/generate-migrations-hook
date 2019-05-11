<?php

namespace MohammedIO;

use Illuminate\Support\Str;
use Illuminate\Events\Dispatcher;
use Illuminate\Support\ServiceProvider;
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
        app(Dispatcher::class)->listen(TableUpdated::class, function (TableUpdated $event) {
            $this->app->make(Handler::class)->handleTableUpdated($event);
        });

        app(Dispatcher::class)->listen(TableAdded::class, function (TableAdded $event) {
            $this->app->make(Handler::class)->handleTableAdded($event);
        });

        app(Dispatcher::class)->listen(TableDeleted::class, function (TableDeleted $event) {
            $this->app->make(Handler::class)->handleTableDeleted($event);
        });
    }
}
