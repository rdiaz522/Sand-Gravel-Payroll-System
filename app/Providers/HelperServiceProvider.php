<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class HelperServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $filenames = glob(app_path('/Http/Helpers.php'));

        if ($filenames !== false && is_iterable($filenames)) {
            foreach ($filenames as $filename) {
                require_once $filename;
            }
        }
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
