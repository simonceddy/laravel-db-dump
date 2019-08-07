<?php
namespace Eddy\DbDump\Provider;

use Illuminate\Support\ServiceProvider;

class DbDumpProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                \Eddy\DbDump\DbDumpCommand::class
            ]);
        }
    }
}
