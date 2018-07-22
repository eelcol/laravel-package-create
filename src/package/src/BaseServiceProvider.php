<?php 

/**
* Base service provider class that can be used in other packages
*
* Part of package: EelcoLuurtsema/LaravelPackageCreate
* Author: Eelco Luurtsema
*/
namespace #NAMESPACE#;

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;
use #NAMESPACE#\ExtendedSchedule;

class BaseServiceProvider extends \Illuminate\Support\ServiceProvider
{
    /**
    * Array of commands
    */
    protected $commands = [];

    /**
    * Array of classes (packages) that must be registered
    */
    protected $register = [];

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        /**
        * Before register
        */
        if(method_exists($this, 'beforeRegistration'))
        {
            $this->beforeRegistration();
        }

        /**
        * Add commands
        */
        $this->commands($this->commands);

        /**
        * Register debugbarother packages
        */
        foreach($this->register AS $class)
        {
            $this->app->register($class);
        }

        /**
        * After register
        */
        if(method_exists($this, 'afterRegistration'))
        {
            $this->afterRegistration();
        }
    }

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot(Router $router)
    {   
        /**
        * Before boot
        */
        if(method_exists($this, 'beforeBoot'))
        {
            $this->beforeBoot();
        }

        /**
        * Set the views directory
        */
        $namespace = strtolower(__NAMESPACE__);
        $namespace = substr($namespace, strpos($namespace, "\\")+1);

        $this->loadViewsFrom(__DIR__.'/resources/views/', $namespace);

        /**
        * Load migrations from
        */
        $this->loadMigrationsFrom(__DIR__.'/../migrations');

        /**
        * Package translations
        */
        $this->loadTranslationsFrom(__DIR__.'/../translations', $namespace);

        /**
        * Publish views
        */
        $this->publishes([
            $this->getThisPath("/resources/publish/views/")                     => resource_path("views/"),
        ]);

        /**
        * After app is booted: Schedule commands
        */
        $object = $this;
        $this->app->booted(function() use($object) {
            $schedule = $this->app->make(ExtendedSchedule::class);

            /**
            * Call schedule function
            */
            if(method_exists($object, 'schedule'))
            {
                $this->schedule($schedule);
            }
        });

        /**
        * Register web routes
        */
        if(file_exists($this->getThisPath("/routes/web.php")))
        {
            Route::group([

                'middleware'    => ['web'],
                'namespace'     => '',

            ], function ($router) {

                require $this->getThisPath("/routes/web.php");

            });
        }
    }

    /**
    * Add an alias
    */
    protected function alias($alias, $class)
    {
        $loader = \Illuminate\Foundation\AliasLoader::getInstance();
        $loader->alias($alias, $class);
    }

    /**
     * Get the active router.
     *
     * @return Router
     */
    protected function getRouter()
    {
        return $this->app['router'];
    }

    /**
     * Check the App Debug status
     */
    protected function checkAppDebug()
    {
        return $this->app['config']->get('app.debug');
    }

    /**
    * Get path of class that extends this class
    */
    public function getThisPath($file='')
    {
        $reflection = new \ReflectionClass($this);
        $directory = dirname($reflection->getFileName());
        if(substr($file, 0, 1) != PATH_SEPARATOR) $directory .= PATH_SEPARATOR;

        return $directory . $file;
    }
}
