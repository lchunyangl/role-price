<?php


namespace Chunyang\RolePrice;


class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom($this->configPath(), 'role-price');
    }

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([$this->configPath() => config_path('role-price.php')], 'config');

        $this->loadRoutesFrom(__DIR__ . '/routes/web.php');

        $this->loadViewsFrom(__DIR__ . '/views', 'role-price');
    }

    protected function configPath()
    {
        return __DIR__ . '/../config/role-price.php';
    }
}