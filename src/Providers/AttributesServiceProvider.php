<?php

declare(strict_types=1);

namespace Rinvex\Attributes\Providers;

use Illuminate\Support\ServiceProvider;
use Rinvex\Attributes\Models\Type\Text;
use Rinvex\Attributes\Models\Type\Boolean;
use Rinvex\Attributes\Models\Type\Integer;
use Rinvex\Attributes\Models\Type\Varchar;
use Rinvex\Attributes\Models\Type\Datetime;
use Rinvex\Attributes\Models\Type\Custom;
use Rinvex\Attributes\Contracts\AttributeContract;
use Rinvex\Attributes\Console\Commands\MigrateCommand;
use Rinvex\Attributes\Contracts\AttributeEntityContract;

class AttributesServiceProvider extends ServiceProvider
{
    /**
     * The commands to be registered.
     *
     * @var array
     */
    protected $commands = [
        MigrateCommand::class => 'command.rinvex.attributes.migrate',
    ];

    /**
     * {@inheritdoc}
     */
    public function register()
    {
        // Merge config
        $this->mergeConfigFrom(realpath(__DIR__.'/../../config/config.php'), 'rinvex.attributes');

        // Bind eloquent models to IoC container
        $this->app->singleton('rinvex.attributes.attribute', function ($app) {
            return new $app['config']['rinvex.attributes.models.attribute']();
        });
        $this->app->alias('rinvex.attributes.attribute', AttributeContract::class);

        $this->app->singleton('rinvex.attributes.attribute_entity', function ($app) {
            return new $app['config']['rinvex.attributes.models.attribute_entity']();
        });
        $this->app->alias('rinvex.attributes.attribute_entity', AttributeEntityContract::class);

        // Register attributes types
        $this->app->singleton('rinvex.attributes.types', function ($app) {
            return collect();
        });

        // Register attributes entities
        $this->app->singleton('rinvex.attributes.entities', function ($app) {
            return collect();
        });

        // Register console commands
        ! $this->app->runningInConsole() || $this->registerCommands();
    }

    /**
     * {@inheritdoc}
     */
    public function boot()
    {
        // Add default attributes types
        app('rinvex.attributes.types')->push(Text::class);
        app('rinvex.attributes.types')->push(Boolean::class);
        app('rinvex.attributes.types')->push(Integer::class);
		app('rinvex.attributes.types')->push(Decimal::class);
        app('rinvex.attributes.types')->push(Varchar::class);
        app('rinvex.attributes.types')->push(Datetime::class);
		app('rinvex.attributes.types')->push(Custom::class);

        // Load migrations
        ! $this->app->runningInConsole() || $this->loadMigrationsFrom(__DIR__.'/../../database/migrations');

        // Publish Resources
        ! $this->app->runningInConsole() || $this->publishResources();
    }

    /**
     * Publish resources.
     *
     * @return void
     */
    protected function publishResources()
    {
        $this->publishes([realpath(__DIR__.'/../../config/config.php') => config_path('rinvex.attributes.php')], 'rinvex-attributes-config');
        $this->publishes([realpath(__DIR__.'/../../database/migrations') => database_path('migrations')], 'rinvex-attributes-migrations');
    }

    /**
     * Register console commands.
     *
     * @return void
     */
    protected function registerCommands()
    {
        // Register artisan commands
        foreach ($this->commands as $key => $value) {
            $this->app->singleton($value, function ($app) use ($key) {
                return new $key();
            });
        }

        $this->commands(array_values($this->commands));
    }
}
