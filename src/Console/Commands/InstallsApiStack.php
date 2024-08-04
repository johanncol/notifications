<?php

namespace Johanncol\Notifications\Console\Commands;

use Illuminate\Filesystem\Filesystem;

trait InstallsApiStack
{

    /**
     * Install the API Breeze stack.
     *
     * @return int|null
     */
    protected function installApiStack()
    {
        // $this->runCommands(['php artisan install:api']);

        $files = new Filesystem;

        // Controllers...
        // $files->ensureDirectoryExists(app_path('Http/Controllers/Auth'));
        // $files->copyDirectory(__DIR__.'/../../stubs/api/app/Http/Controllers/Auth', app_path('Http/Controllers/Auth'));

        // Middleware...
        // $files->copyDirectory(__DIR__.'/../../stubs/api/app/Http/Middleware', app_path('Http/Middleware'));

        // Requests...
        $files->ensureDirectoryExists(app_path('Http/Requests/Auth'));
        $files->copyDirectory(__DIR__.'/../../stubs/api/app/Http/Requests/Auth', app_path('Http/Requests/Auth'));

        // Providers...
        $files->copyDirectory(__DIR__.'/../../stubs/api/app/Providers', app_path('Providers'));

        // Routes...
        copy(__DIR__.'/../../stubs/api/routes/notifications.php', base_path('routes/notifications.php'));

        // Configuration...
        // $files->copyDirectory(__DIR__.'/../../stubs/api/config', config_path());

        // Environment...
        if (! $files->exists(base_path('.env')))
        {
            copy(base_path('.env.example'), base_path('.env'));
        }

        file_put_contents(
            base_path('.env'),
            preg_replace('/APP_URL=(.*)/', 'APP_URL=http://localhost:7777'.PHP_EOL.'FRONTEND_URL=http://localhost:7777', file_get_contents(base_path('.env')))
        );

        $this->installRoute();

        // Tests...
        // if (! $this->installTests()) {
        //     return 1;
        // }
        // $files->delete(base_path('tests/Feature/Auth/PasswordConfirmationTest.php'));

        // Cleaning...
        // $this->removeScaffoldingUnnecessaryForApis();

        $this->components->info('Notificaciones instalada OK.');
    }

    /**
     * Remove any application scaffolding that isn't needed for APIs.
     *
     * @return void
     */
    protected function removeScaffoldingUnnecessaryForApis()
    {
        $files = new Filesystem;

        // Remove frontend related files...
        $files->delete(base_path('package.json'));
        $files->delete(base_path('vite.config.js'));

        // Remove Laravel "welcome" view...
        $files->delete(resource_path('views/welcome.blade.php'));
        $files->put(resource_path('views/.gitkeep'), PHP_EOL);

        // Remove CSS and JavaScript directories...
        $files->deleteDirectory(resource_path('css'));
        $files->deleteDirectory(resource_path('js'));
    }


    protected function installRoute( )
    {
        $bootstrapApp = file_get_contents(base_path('routes/web.php'));

        file_put_contents( base_path('routes/web.php') , 'textto'.PHP_EOL.PHP_EOL, FILE_APPEND );

        $this->info("<bg=yellow;fg=black> RUTA:: </> OK!");
    }

    
}
