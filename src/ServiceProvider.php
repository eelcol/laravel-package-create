<?php 

/**
* Default service provider
*/
namespace EelcoLuurtsema\LaravelPackageCreate;

use EelcoLuurtsema\LaravelPackageCreate\App\Console\Commands\MakePackage;
use EelcoLuurtsema\LaravelPackageCreate\BaseServiceProvider;
use Illuminate\Routing\Router;

class ServiceProvider extends BaseServiceProvider
{
    /**
    * Array of commands
    * Insert any classes here to register them as commands
    */
    protected $commands = [
        MakePackage::class,
    ];

    /**
    * Array of classes (packages) that must be registered
    * Insert any classes here to register them (instead of using config/app.php)
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
        * Call parent function
        */
        parent::register();
    }

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot(Router $router)
    {  
        /**
        * Call parent function
        */
        parent::boot($router);
        
        /**
        * Do stuff here like publishing files
        */
        //$this->publishes([
        //    __DIR__.'/../config/laratron.php'                       => config_path('file.php'),
        //]);
        
        /**
        * Or register routes
        */
        // Route::get('test','test');
    }

    /**
    * Schedule commands
    */
    public function schedule($Schedule)
    {
        // Examples:
        // $schedule->command('command')->daily()->at('12:00');
        // $schedule->commandAtRandomTime('command');  // This command will create a new time every day
        // $schedule->commandAtRandomTimeBetween('command', 0, 5);  // This command will create a new time every day between 0:00 and 5:00
    }
}
