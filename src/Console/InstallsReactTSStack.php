<?php

namespace Laravel\Breeze\Console;

use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Process\Process;

trait InstallsReactTSStack
{
    /**
     * Install the Inertia Vue Breeze stack.
     *
     * @return void
     */

    protected function installInertiaReactTSStack()
    {
        // Install Inertia...
        $this->requireComposerPackages('inertiajs/inertia-laravel:^0.6.3', 'laravel/sanctum:^2.8', 'tightenco/ziggy:^1.0', 'laravel/breeze:^1.14.3');

        // NPM Packages...
        $this->updateNodePackages(function ($packages) {
            return [
                "@types/react"=> "^17.0.43",
                "@types/react-dom"=> "^17.0.14",
                '@headlessui/react' => '^1.4.2',
                '@inertiajs/inertia' => '^0.11.0',
                '@inertiajs/inertia-react' => '^0.8.0',
                '@inertiajs/progress' => '^0.2.6',
                '@tailwindcss/forms' => '^0.4.0',
                '@vitejs/plugin-react' => '^2.0.0',
                'autoprefixer' => '^10.4.2',
                'postcss' => '^8.4.6',
                'postcss-import' => '^14.0.2',
                'tailwindcss' => '^3.0.18',
                'react' => '^17.0.2',
                'react-dom' => '^17.0.2',
                '@babel/preset-react' => '^7.16.7',
            ] + $packages;
        });

        // Controllers...
        (new Filesystem)->ensureDirectoryExists(app_path('Http/Controllers/Auth'));
        (new Filesystem)->copyDirectory(__DIR__.'/../../stubs/inertia-common/app/Http/Controllers/Auth', app_path('Http/Controllers/Auth'));

        // Requests...
        (new Filesystem)->ensureDirectoryExists(app_path('Http/Requests/Auth'));
        (new Filesystem)->copyDirectory(__DIR__.'/../../stubs/default/app/Http/Requests/Auth', app_path('Http/Requests/Auth'));

        // Middleware...
        $this->installMiddlewareAfter('SubstituteBindings::class', '\App\Http\Middleware\HandleInertiaRequests::class');

        copy(__DIR__.'/../../stubs/inertia-common/app/Http/Middleware/HandleInertiaRequests.php', app_path('Http/Middleware/HandleInertiaRequests.php'));

        // Views...
        copy(__DIR__.'/../../stubs/inertia-common/resources/views/app.blade.php', resource_path('views/app.blade.php'));
        $this->replaceInFile("@vite('resources/js/app.js')", '@viteReactRefresh'.PHP_EOL."        @vite('resources/js/app.jsx')", resource_path('views/app.blade.php'));

        // Components + Pages...
        (new Filesystem)->ensureDirectoryExists(resource_path('js/Components'));
        (new Filesystem)->ensureDirectoryExists(resource_path('js/Layouts'));
        (new Filesystem)->ensureDirectoryExists(resource_path('js/Pages'));

        (new Filesystem)->copyDirectory(__DIR__.'/../../stubs/inertia-react-ts/resources/ts/Components', resource_path('js/Components'));
        (new Filesystem)->copyDirectory(__DIR__.'/../../stubs/inertia-react-ts/resources/ts/Layouts', resource_path('js/Layouts'));
        (new Filesystem)->copyDirectory(__DIR__.'/../../stubs/inertia-react-ts/resources/ts/Pages', resource_path('js/Pages'));

        // Tests...
        $this->installTests();

        // Routes...
        copy(__DIR__.'/../../stubs/inertia-common/routes/web.php', base_path('routes/web.php'));
        copy(__DIR__.'/../../stubs/inertia-common/routes/auth.php', base_path('routes/auth.php'));

        // "Dashboard" Route...
        $this->replaceInFile('/home', '/dashboard', resource_path('js/Pages/Welcome.tsx'));
        $this->replaceInFile('Home', 'Dashboard', resource_path('js/Pages/Welcome.tsx'));
        $this->replaceInFile('/home', '/dashboard', app_path('Providers/RouteServiceProvider.php'));

        // Tailwind / Vite...
        copy(__DIR__.'/../../stubs/default/resources/css/app.css', resource_path('css/app.css'));
        copy(__DIR__.'/../../stubs/default/postcss.config.js', base_path('postcss.config.js'));
        copy(__DIR__.'/../../stubs/inertia-common/tailwind.config.js', base_path('tailwind.config.js'));
        copy(__DIR__.'/../../stubs/inertia-common/jsconfig.json', base_path('jsconfig.json'));
        copy(__DIR__.'/../../stubs/inertia-react/vite.config.js', base_path('vite.config.js'));
        copy(__DIR__.'/../../stubs/inertia-react-ts/resources/ts/bootstrap.js', resource_path('js/bootstrap.js'));
        copy(__DIR__.'/../../stubs/inertia-react-ts/resources/ts/app.tsx', resource_path('js/app.tsx'));

        if (file_exists(resource_path('js/app.js'))) {
            unlink(resource_path('js/app.js'));
        }

        $this->replaceInFile('.vue', '.jsx', base_path('tailwind.config.js'));
        $this->replaceInFile('.jsx', '.tsx', base_path('vite.config.js'));
        $this->replaceInFile('.jsx', '.tsx', resource_path('views/app.blade.php'));

        if ($this->option('ssr')) {
            $this->installInertiaReactSsrStack();
        }

        $this->runCommands(['npm install', 'npm run build']);

        $this->line('');
        $this->components->info('Breeze scaffolding installed successfully.');
    }
}
