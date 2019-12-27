<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->instance('path.config', app()->basePath() . DIRECTORY_SEPARATOR . 'config');
    }

    public function boot()
    {
        // 加载配置文件
        $configDir = $this->app->make('path.config');

        if (!is_dir($configDir))
            return;

        foreach (scandir($configDir) as $key => $value) {
            if(is_file($configDir . DIRECTORY_SEPARATOR . $value)) {
                $value = rtrim($value, '.php');
                $this->app->configure($value);
            }
        }
    }
}
