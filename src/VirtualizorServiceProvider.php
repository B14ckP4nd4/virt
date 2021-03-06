<?php


    namespace blackpanda\virt;

    use Illuminate\Foundation\AliasLoader;
    use Illuminate\Support\ServiceProvider;

    class VirtualizorServiceProvider extends ServiceProvider
    {

        public function register()
        {
            //parent::register(); // TODO: Change the autogenerated stub

            // register Package
            $this->app->bind('virtualizor', function () {
                return new Virtualizor();
            });

            // register Facades
            $loader = AliasLoader::getInstance();
            $loader->alias('Virtualizor', 'blackpanda\virt\VirtualizorFacades');

            // register events

            $this->app->register(VirtualizorEventServiceProvider::class);
        }


        public function boot()
        {
            // Register Publishes

            // Migrations

            $this->publishes([
                __DIR__ . '/../publishes/migrations' => database_path('/migrations'),
            ], 'Virt-migrations');

            // Models

            $this->publishes([
                __DIR__ . '/../publishes/models' => app_path('/virt'),
            ], 'Virt-models');

            // Factories

            $this->publishes([
                __DIR__ . '/../publishes/factories' => database_path('/factories'),
            ], 'Virt-models');

            // Config

            $this->publishes([
                __DIR__ . '/../publishes/configs' => config_path('/'),
            ], 'Virt-config');

            // Events

            $this->publishes([
                __DIR__ . '/../publishes/Events' => app_path('/Events'),
            ], 'Virt-events');

            // Events

            $this->publishes([
                __DIR__ . '/../publishes/Listeners' => app_path('/Listeners'),
            ], 'Virt-events');

        }

    }
