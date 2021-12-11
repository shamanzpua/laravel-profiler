<?php


namespace shamanzpua\LaravelProfiler;

use Illuminate\Support\ServiceProvider;
use shamanzpua\LaravelProfiler\Contracts\IExtraOptionFactory;
use shamanzpua\LaravelProfiler\Contracts\ILogCleaner;
use shamanzpua\LaravelProfiler\Contracts\ILogProvider;
use shamanzpua\LaravelProfiler\ExtraOptions\Factory;
use shamanzpua\Profiler\Contracts\ILogStorage;
use shamanzpua\Profiler\LogStorages\FileStorage;
use shamanzpua\Profiler\Profiler;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use shamanzpua\LaravelProfiler\Middleware\AuthCodeMiddleware;

class ProfilerServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to the controller routes in your routes file.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'Company\Package\Http\Controllers';



    /**
     * Define the routes for the application.
     *
     * @param  \Illuminate\Routing\Router  $router
     * @return void
     */
    protected function addRoutes()
    {
        $this->app['router']->aliasMiddleware(AuthCodeMiddleware::NAME, AuthCodeMiddleware::class);

        Route::group(['namespace' => 'shamanzpua\LaravelProfiler\Controllers'], function ($router) {
            require  __DIR__ . '/configs/routes.php';
        });
    }
    protected function addViewPath()
    {
        View::addLocation(__DIR__ . '/views');
    }

    protected function bindExtraOptionFactory()
    {
        $this->app->bind(IExtraOptionFactory::class, Factory::class);
    }

    protected function setupCustomProfilers()
    {
        Profiler::getInstance()
            ->setCustomProfiler(new DBQueriesProfiler());
    }

    protected function bindStorageClasses()
    {
        $currentLogStorage = config("code-profiler.storage");
        $storageConfig = config("code-profiler.storages.$currentLogStorage");
        $this->app->bind(ILogProvider::class, $storageConfig['class']);
        $this->app->bind(ILogStorage::class, $storageConfig['class']);
        $this->app->bind(ILogCleaner::class, $storageConfig['class']);
    }

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/configs/profiler.php', 'code-profiler'
        );
        $this->addViewPath();
        $this->addRoutes();
        $this->bindExtraOptionFactory();
        $this->bindStorageClasses();

        Profiler::getInstance()
            ->setLogStorage(app(ILogStorage::class))
            ->setLogDurationThreshold(config("code-profiler.log_duration_threshold"));

        $this->setupCustomProfilers();
    }
}
