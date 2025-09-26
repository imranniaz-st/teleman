<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use File;
use Cache;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * This is used by Laravel authentication to redirect users after login.
     *
     * @var string
     */
    public const HOME = '/dashboard';

    /**
     * The controller namespace for the application.
     *
     * When present, controller route declarations will automatically be prefixed with this namespace.
     *
     * @var string|null
     */
    // protected $namespace = 'App\\Http\\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        $this->configureRateLimiting();

        $this->routes(function () {
            $excludedFiles = ['channels.php', 'api.php', 'console.php'];

            Route::prefix('api')
                ->middleware('api')
                ->namespace($this->namespace)
                ->group(base_path('routes/api.php'));

            $routeFiles = Cache::remember('route_files', 60, function () use ($excludedFiles) {
                $filesInFolder = File::files(base_path('routes'));
                $routeFiles = [];

                foreach ($filesInFolder as $file) {
                    $filename = $file->getFilename();

                    if (!in_array($filename, $excludedFiles)) {
                        $routeFiles[] = $file->getPathname();
                    }
                }

                return $routeFiles;
            });

            foreach ($routeFiles as $filePath) {
                Route::middleware('web')
                    ->namespace($this->namespace)
                    ->group($filePath);
            }
        });
    }

    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(1000)->by(optional($request->user())->id ?: $request->ip());
        });
    }
}
