<?php

namespace Expstudio\LumenStapler\Providers;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use Expstudio\LumenStapler\Commands\RefreshCommand;
use Expstudio\LumenStapler\Services\ImageRefreshService;

abstract class ServiceProvider extends BaseServiceProvider
{
    /**
     * Bootstrap up the stapler package:
     * - Boot stapler.
     * - Set the config driver.
     * - Set public_path config using lumen's public_path() method (if necessary).
     * - Set base_path config using lumen's base_path() method (if necessary).
     */
    abstract protected function bootstrapStapler();

    /**
     * Register the stapler fasten command with the container.
     */
    abstract protected function registerStaplerFastenCommand();

    /**
     * Register the service provider.
     */
    public function register()
    {
        // commands
        $this->registerStaplerFastenCommand();
        $this->registerStaplerRefreshCommand();

        // services
        $this->registerImageRefreshService();

        $this->commands('stapler.fasten');
        $this->commands('stapler.refresh');
    }

    /**
     * Register the stapler refresh command with the container.
     */
    protected function registerStaplerRefreshCommand()
    {
        $this->app->bind('stapler.refresh', function ($app) {
            $refreshService = $app['ImageRefreshService'];

            return new RefreshCommand($refreshService);
        });
    }

    /**
     * Register the image refresh service with the container.
     */
    protected function registerImageRefreshService()
    {
        $this->app->singleton('ImageRefreshService', function ($app) {
            return new ImageRefreshService($app);
        });
    }
}
