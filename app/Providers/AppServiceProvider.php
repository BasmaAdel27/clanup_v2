<?php

namespace App\Providers;

use App\Models\Integration;
use App\Services\Notification\ChannelManager;
use App\Services\Language\Drivers\Translation;
use App\Services\Language\Scanner;
use App\Services\Language\TranslationManager;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(ChannelManager::class, function ($app) {
            return new ChannelManager($app);
        });

        $this->app->singleton(Scanner::class, function () {
            $config = $this->app['config']['translation'];

            return new Scanner(new Filesystem, $config['scan_paths'], $config['translation_methods']);
        });

        $this->app->singleton(Translation::class, function ($app) {
            return (new TranslationManager($app, $app['config']['translation'], $app->make(Scanner::class)))->resolve();
        });

        if (is_installed()) {
            // Share auth_user variable with all blade views
            View::composer('*', function ($view) {
                $view->with('auth_user',  auth()->user());
                $view->with('application_name', get_system_setting('application_name'));
            });
        } 
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if (Schema::hasTable('integrations')) {
            // Register Integration Service Providers
            $integrations = Integration::where('is_active', true)->get();
            foreach ($integrations as $integration) {
                $class_name = Str::studly($integration->slug);
                $config = app_path('Services/Integrations/'.$class_name.'/config.php');
                $this->mergeConfigFrom(
                    $config, $integration->slug
                );

                $moduleProviders = config($integration->slug. '.providers', []);
                foreach ($moduleProviders as $provider) {
                    $this->app->register($provider);
                }
            }
        } 
    }
}
