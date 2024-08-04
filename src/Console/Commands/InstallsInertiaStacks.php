<?php

namespace Johanncol\Notifications\Console\Commands;

use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

trait InstallsInertiaStacks
{

    /**
     * Install the Inertia Vue Breeze stack.
     *
     * @return int|null
     */
    protected function installInertiaVueStack()
    {


        // Install Inertia...
        if (! $this->requireComposerPackages(['inertiajs/inertia-laravel:^1.0', 'laravel/sanctum:^4.0', 'tightenco/ziggy:^2.0']))
        {
            return 1;
        }

        // NPM Packages...
        $this->updateNodePackages(function ($packages) {
            return [
                '@inertiajs/vue3' => '^1.0.0',
                '@tailwindcss/forms' => '^0.5.3',
                '@vitejs/plugin-vue' => '^5.0.0',
                'autoprefixer' => '^10.4.12',
                'postcss' => '^8.4.31',
                'tailwindcss' => '^3.2.1',
                'vue' => '^3.4.0',
            ] + $packages;
        });

        // Controllers...
        (new Filesystem)->ensureDirectoryExists(app_path('Http/Controllers'));
        (new Filesystem)->copyDirectory(__DIR__.'/../../stubs/inertia-common/app/Http/Controllers', app_path('Http/Controllers'));


        // Requests...
        // (new Filesystem)->ensureDirectoryExists(app_path('Http/Requests'));
        // (new Filesystem)->copyDirectory(__DIR__.'/../../stubs/default/app/Http/Requests', app_path('Http/Requests'));

        // Components + Pages...
        (new Filesystem)->ensureDirectoryExists(resource_path('js/Components'));
        (new Filesystem)->ensureDirectoryExists(resource_path('js/Layouts'));
        (new Filesystem)->ensureDirectoryExists(resource_path('js/Pages'));

        (new Filesystem)->copyDirectory(__DIR__.'/../../stubs/inertia-vue/resources/js/Components', resource_path('js/Components'));
        (new Filesystem)->copyDirectory(__DIR__.'/../../stubs/inertia-vue/resources/js/Layouts', resource_path('js/Layouts'));
        (new Filesystem)->copyDirectory(__DIR__.'/../../stubs/inertia-vue/resources/js/Pages', resource_path('js/Pages'));

        // Routes...
        copy(__DIR__.'/../../stubs/inertia-common/routes/web.php', base_path('routes/web.php'));
        copy(__DIR__.'/../../stubs/inertia-common/routes/auth.php', base_path('routes/auth.php'));

        // Tailwind / Vite...
        copy(__DIR__.'/../../stubs/default/resources/css/app.css', resource_path('css/app.css'));
        copy(__DIR__.'/../../stubs/default/postcss.config.js', base_path('postcss.config.js'));
        copy(__DIR__.'/../../stubs/inertia-common/tailwind.config.js', base_path('tailwind.config.js'));
        copy(__DIR__.'/../../stubs/inertia-vue/vite.config.js', base_path('vite.config.js'));

        if ($this->option('typescript')) {
            copy(__DIR__.'/../../stubs/inertia-vue-ts/tsconfig.json', base_path('tsconfig.json'));
            copy(__DIR__.'/../../stubs/inertia-vue-ts/resources/js/app.ts', resource_path('js/app.ts'));

            if (file_exists(resource_path('js/app.js'))) {
                unlink(resource_path('js/app.js'));
            }

            if (file_exists(resource_path('js/bootstrap.js'))) {
                rename(resource_path('js/bootstrap.js'), resource_path('js/bootstrap.ts'));
            }

            $this->replaceInFile('"vite build', '"vue-tsc && vite build', base_path('package.json'));
            $this->replaceInFile('.js', '.ts', base_path('vite.config.js'));
            $this->replaceInFile('.js', '.ts', resource_path('views/app.blade.php'));
        } else {
            copy(__DIR__.'/../../stubs/inertia-common/jsconfig.json', base_path('jsconfig.json'));
            copy(__DIR__.'/../../stubs/inertia-vue/resources/js/app.js', resource_path('js/app.js'));
        }


        $this->components->info('[JOHANN][0000]Installing and building Node dependencies.');

        if (file_exists(base_path('pnpm-lock.yaml'))) {
            $this->runCommands(['pnpm install', 'pnpm run build']);
        } elseif (file_exists(base_path('yarn.lock'))) {
            $this->runCommands(['yarn install', 'yarn run build']);
        } elseif (file_exists(base_path('bun.lockb'))) {
            $this->runCommands(['bun install', 'bun run build']);
        } else {
            $this->runCommands(['npm install', 'npm run build']);
        }

        $this->line('');
        $this->components->info('[JOHANN][INSTALACIÓN TERMINADA]');
    }


    /**
     * Install the Inertia React Breeze stack.
     *
     * @return int|null
     */
    protected function installInertiaReactStack()
    {
        // Install Inertia...
        if (! $this->requireComposerPackages(['inertiajs/inertia-laravel:^1.0', 'laravel/sanctum:^4.0', 'tightenco/ziggy:^2.0'])) {
            return 1;
        }

        // NPM Packages...
        $this->updateNodePackages(function ($packages) {
            return [
                '@headlessui/react' => '^2.0.0',
                '@inertiajs/react' => '^1.0.0',
                '@tailwindcss/forms' => '^0.5.3',
                '@vitejs/plugin-react' => '^4.2.0',
                'autoprefixer' => '^10.4.12',
                'postcss' => '^8.4.31',
                'tailwindcss' => '^3.2.1',
                'react' => '^18.2.0',
                'react-dom' => '^18.2.0',
            ] + $packages;
        });

        // Controllers...
        (new Filesystem)->ensureDirectoryExists(app_path('Http/Controllers'));
        (new Filesystem)->copyDirectory(__DIR__.'/../../stubs/inertia-common/app/Http/Controllers', app_path('Http/Controllers'));

        // Requests...
        (new Filesystem)->ensureDirectoryExists(app_path('Http/Requests'));
        (new Filesystem)->copyDirectory(__DIR__.'/../../stubs/default/app/Http/Requests', app_path('Http/Requests'));

        // Middleware...
        $this->installMiddleware([
            '\App\Http\Middleware\HandleInertiaRequests::class',
            '\Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets::class',
        ]);

        (new Filesystem)->ensureDirectoryExists(app_path('Http/Middleware'));
        copy(__DIR__.'/../../stubs/inertia-common/app/Http/Middleware/HandleInertiaRequests.php', app_path('Http/Middleware/HandleInertiaRequests.php'));

        // Views...
        copy(__DIR__.'/../../stubs/inertia-react/resources/views/app.blade.php', resource_path('views/app.blade.php'));

        @unlink(resource_path('views/welcome.blade.php'));

        // Components + Pages...
        (new Filesystem)->ensureDirectoryExists(resource_path('js/Components'));
        (new Filesystem)->ensureDirectoryExists(resource_path('js/Layouts'));
        (new Filesystem)->ensureDirectoryExists(resource_path('js/Pages'));
        
        (new Filesystem)->copyDirectory(__DIR__.'/../../stubs/inertia-react/resources/js/Components', resource_path('js/Components'));
        (new Filesystem)->copyDirectory(__DIR__.'/../../stubs/inertia-react/resources/js/Layouts', resource_path('js/Layouts'));
        (new Filesystem)->copyDirectory(__DIR__.'/../../stubs/inertia-react/resources/js/Pages', resource_path('js/Pages'));


        // Routes...
        copy(__DIR__.'/../../stubs/inertia-common/routes/web.php', base_path('routes/web.php'));
        copy(__DIR__.'/../../stubs/inertia-common/routes/auth.php', base_path('routes/auth.php'));

        // Tailwind / Vite...
        copy(__DIR__.'/../../stubs/default/resources/css/app.css', resource_path('css/app.css'));
        copy(__DIR__.'/../../stubs/default/postcss.config.js', base_path('postcss.config.js'));
        copy(__DIR__.'/../../stubs/inertia-common/tailwind.config.js', base_path('tailwind.config.js'));
        copy(__DIR__.'/../../stubs/inertia-react/vite.config.js', base_path('vite.config.js'));



        $this->components->info('Installing and building Node dependencies.');

        if (file_exists(base_path('pnpm-lock.yaml'))) {
            $this->runCommands(['pnpm install', 'pnpm run build']);
        } elseif (file_exists(base_path('yarn.lock'))) {
            $this->runCommands(['yarn install', 'yarn run build']);
        } elseif (file_exists(base_path('bun.lockb'))) {
            $this->runCommands(['bun install', 'bun run build']);
        } else {
            $this->runCommands(['npm install', 'npm run build']);
        }

        $this->line('');
        $this->components->info('Johann OK.');
    }

}
