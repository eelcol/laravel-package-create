<?php

namespace EelcoLuurtsema\LaravelPackageCreate\App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakePackage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:package';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make a package for Laravel';

    /**
    * Parent category
    */
    protected $parent = false;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        /**
        * Ask for top-level folder
        */
        $toplevel_folder = $this->ask('Please enter the top-level folder name. Eg: barryvdh');

        /**
        * Ask for sublevel folder
        */
        $sublevel_folder = $this->ask('Please enter sub-level folder name. Eg: laravel-debugbar');

        /**
        * Ask for the package namespace
        */
        $namespace = $this->ask('Please enter the package namespace. Eg: Social\Facebook');

        /**
        * Check the value
        */
        $nsExplode = explode("\\", $namespace);
        if(count($nsExplode) != 2)
        {
            $this->error("You must supply a correct namespace. Please use format: Social\Facebook.");

            /**
            * Retry
            */
            $this->handle();
            return false;
        }

        /**
        * Create path
        */
        $path = base_path("packages/" . $toplevel_folder . "/" . $sublevel_folder);
        if (!File::exists($path))
        {
            File::makeDirectory($path, 0755, true);
        }

        /**
        * Now copy source folder
        */
        $success = File::copyDirectory(__DIR__ . "/../../../package", $path);

        /**
        * Create empty folders
        */
        $folders = ['migrations','translations','src/app','src/app/Console','src/app/Console/Commands','src/app/Http/Controllers','src/app/Models','src/resources','src/resources/publish','src/resources/publish/views','src/resources/views'];
        foreach($folders AS $folder)
        {
            $folderPath = $path . "/" . $folder;
            if (!File::exists($folderPath)) {
                File::makeDirectory($folderPath, 0755, true);
            }
        }

        /**
        * Change variables in copied files
        */
        $variables = [];
        $variables['TOPLEVELFOLDER'] = $toplevel_folder;
        $variables['SUBLEVELFOLDER'] = $sublevel_folder;
        $variables['NAMESPACE']      = $namespace;
        $variables['NAMESPACE1']     = $nsExplode[0];
        $variables['NAMESPACE2']     = $nsExplode[1];

        $files = [];
        $files[] = 'composer.json';
        $files[] = 'readme.md';
        $files[] = 'src/BaseServiceProvider.php';
        $files[] = 'src/ExtendedSchedule.php';
        $files[] = 'src/ServiceProvider.php';

        /**
        * Loop files
        */
        foreach($files AS $file)
        {
            $filepath = $path . "/" . $file;

            $contents = @file_get_contents($filepath);
            if($contents)
            {
                foreach($variables AS $varname => $varvalue) $contents = str_replace("#" . $varname . "#", $varvalue, $contents);

                file_put_contents($filepath, $contents);
            }
        }

        /**
        * Create output
        */
        $this->info("Now please change ServiceProvider.php, composer.json and readme.md. Then run composer update in that directory.");
    }

}
