<?php

namespace Drewmw5\Breeze\Console;

use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
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
        $this->requireComposerPackages(
            'inertiajs/inertia-laravel:^0.6.3',
            'laravel/sanctum:^2.8',
            'tightenco/ziggy:^1.0',
            'laravel/breeze:^1.14.3'
        );

        // NPM Packages...
        $this->updateNodePackages(function ($packages) {
            return [
                '@babel/preset-react' => '^7.16.7',
                '@headlessui/react' => '^1.4.2',
                '@inertiajs/inertia' => '^0.11.0',
                '@inertiajs/inertia-react' => '^0.8.1',
                '@inertiajs/progress' => '^0.2.6',
                '@tailwindcss/forms' => '^0.5.3',
                "@types/react"=> "^18.0.25",
                "@types/react-dom"=> "^18.0.9",
                "@types/ziggy-js" => "^1.3.2",
                '@vitejs/plugin-react' => '^2.0.0',
                'autoprefixer' => '^10.4.12',
                'postcss' => '^8.4.18',
                'react' => '^18.2.0',
                'react-dom' => '^18.2.0',
                'tailwindcss' => '^3.2.1',
            ] + $packages;
        });

        // Controllers...
        (new Filesystem)->ensureDirectoryExists(app_path('Http/Controllers'));
        (new Filesystem)->copyDirectory(__DIR__.'/../../stubs/inertia-common/app/Http/Controllers', app_path('Http/Controllers'));

        // Requests...
        (new Filesystem)->ensureDirectoryExists(app_path('Http/Requests'));
        (new Filesystem)->copyDirectory(__DIR__.'/../../stubs/default/app/Http/Requests', app_path('Http/Requests'));

        // Middleware...
        $this->installMiddlewareAfter('SubstituteBindings::class', '\App\Http\Middleware\HandleInertiaRequests::class');
        $this->installMiddlewareAfter('\App\Http\Middleware\HandleInertiaRequests::class', '\Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets::class');

        copy(__DIR__.'/../../stubs/inertia-common/app/Http/Middleware/HandleInertiaRequests.php', app_path('Http/Middleware/HandleInertiaRequests.php'));

        // Views...
        copy(__DIR__.'/../../stubs/inertia-react-ts/resources/views/app.blade.php', resource_path('views/app.blade.php'));
        $this->replaceInFile("@vite('resources/js/app.js')", '@viteReactRefresh'.PHP_EOL."        @vite('resources/js/app.tsx')", resource_path('views/app.blade.php'));

        // Components + Pages...
        (new Filesystem)->ensureDirectoryExists(resource_path('js/Components'));
        (new Filesystem)->ensureDirectoryExists(resource_path('js/Layouts'));
        (new Filesystem)->ensureDirectoryExists(resource_path('js/Pages'));

        (new Filesystem)->copyDirectory(__DIR__.'/../../stubs/inertia-react-ts/resources/ts/Components', resource_path('js/Components'));
        (new Filesystem)->copyDirectory(__DIR__.'/../../stubs/inertia-react-ts/resources/ts/Layouts', resource_path('js/Layouts'));
        (new Filesystem)->copyDirectory(__DIR__.'/../../stubs/inertia-react-ts/resources/ts/Pages', resource_path('js/Pages'));

        // Remove default dark styles if '--dark' argument is not specified
        if (! $this->option('dark')) {
            $this->removeDarkClasses((new Finder)
                ->in(resource_path('js'))
                ->name('*.tsx')
                ->notName('Welcome.tsx')
            );
        }

        // Tests...
        $this->installTests();
        (new Filesystem)->copyDirectory(__DIR__.'/../../stubs/inertia-common/tests/Feature', base_path('tests/Feature'));

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
        copy(__DIR__.'/../../stubs/inertia-react-ts/vite.config.ts', base_path('vite.config.js'));
        copy(__DIR__.'/../../stubs/inertia-react-ts/tsconfig.json', base_path('tsconfig.json'));
        copy(__DIR__.'/../../stubs/inertia-react-ts/resources/ts/app.tsx', resource_path('js/app.tsx'));

        if (file_exists(resource_path('js/app.js'))) {
            unlink(resource_path('js/app.js'));
        }

        $this->replaceInFile('.vue', '.tsx', base_path('tailwind.config.js'));
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
