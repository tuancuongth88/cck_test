<?php

namespace Tests;

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;

trait CreatesApplication
{

    protected static $setUpHasRunOnce = false;

    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $app = require __DIR__ . '/../bootstrap/app.php';

        $app->loadEnvironmentFrom('.env.testing');

        $app->make(Kernel::class)->bootstrap();

        if ($app->env !== 'testing' || config('database.default') !== 'mysql') {

            $output = new \Symfony\Component\Console\Output\ConsoleOutput();
            $output->writeln("");
            $output->writeln(str_repeat('=', 35) . ' NOTE TESTING ' . str_repeat('=', 35));
            $output->writeln("=" . str_repeat(' ', 82) . "=");
            $output->writeln('=     NOTE: YOU NEED TO PERFORM THE FOLLOWING STEPS BEFORE RUNNING THE TEST        =');
            $output->writeln('=     STEP 1. Create a file .env.testing                                           =');
            $output->writeln('=     STEP 2. Run command "php artisan config:clear" before run test               =');
            $output->writeln('=     STEP 3. Run command "php artisan test" or "php artisan test --env=testing"   =');
            $output->writeln("=" . str_repeat(' ', 82) . "=");
            $output->writeln('=============================== END NOTE TESTING ===================================');
            //use died to show the message
            die();
        }

        $conn = Storage::disk('database');
        if (!$conn->exists('database.sqlite')) {
            $conn->put('database.sqlite', null);
        }

        if (!static::$setUpHasRunOnce) {
            Artisan::call('migrate:fresh');
            Artisan::call('db:seed');
            static::$setUpHasRunOnce = true;
        }

        return $app;
    }
}
