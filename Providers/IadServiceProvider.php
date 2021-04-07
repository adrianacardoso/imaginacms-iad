<?php

namespace Modules\Iad\Providers;

use Illuminate\Database\Eloquent\Factory as EloquentFactory;
use Illuminate\Support\ServiceProvider;
use Modules\Core\Traits\CanPublishConfiguration;
use Modules\Core\Events\BuildingSidebar;
use Modules\Core\Events\LoadingBackendTranslations;
use Modules\Iad\Listeners\RegisterIadSidebar;
use Illuminate\Support\Facades\Blade;

class IadServiceProvider extends ServiceProvider
{
  use CanPublishConfiguration;

  /**
   * Indicates if loading of the provider is deferred.
   *
   * @var bool
   */
  protected $defer = false;

  /**
   * Register the service provider.
   *
   * @return void
   */
  public function register()
  {
    $this->registerBindings();
    $this->app['events']->listen(BuildingSidebar::class, RegisterIadSidebar::class);

    $this->app['events']->listen(LoadingBackendTranslations::class, function (LoadingBackendTranslations $event) {
      $event->load('categories', Arr::dot(trans('iad::categories')));
      $event->load('ads', Arr::dot(trans('iad::ads')));
      $event->load('fields', Arr::dot(trans('iad::fields')));
      $event->load('schedules', Arr::dot(trans('iad::schedules')));
      // append translations


    });


  }

  public function boot()
  {
    $this->publishConfig('iad', 'permissions');
    $this->publishConfig('iad', 'config');
    $this->publishConfig('iad', 'crud-fields');
    $this->publishConfig('iad', 'settings');
    $this->publishConfig('iad', 'settings-fields');

    $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
    $this->registerComponents();
  }

  /**
   * Get the services provided by the provider.
   *
   * @return array
   */
  public function provides()
  {
    return array();
  }

  private function registerBindings()
  {
    $this->app->bind(
      'Modules\Iad\Repositories\CategoryRepository',
      function () {
        $repository = new \Modules\Iad\Repositories\Eloquent\EloquentCategoryRepository(new \Modules\Iad\Entities\Category());

        if (!config('app.cache')) {
          return $repository;
        }

        return new \Modules\Iad\Repositories\Cache\CacheCategoryDecorator($repository);
      }
    );
    $this->app->bind(
      'Modules\Iad\Repositories\AdRepository',
      function () {
        $repository = new \Modules\Iad\Repositories\Eloquent\EloquentAdRepository(new \Modules\Iad\Entities\Ad());

        if (!config('app.cache')) {
          return $repository;
        }

        return new \Modules\Iad\Repositories\Cache\CacheAdDecorator($repository);
      }
    );
    $this->app->bind(
      'Modules\Iad\Repositories\FieldRepository',
      function () {
        $repository = new \Modules\Iad\Repositories\Eloquent\EloquentFieldRepository(new \Modules\Iad\Entities\Field());

        if (!config('app.cache')) {
          return $repository;
        }

        return new \Modules\Iad\Repositories\Cache\CacheFieldDecorator($repository);
      }
    );
    $this->app->bind(
      'Modules\Iad\Repositories\ScheduleRepository',
      function () {
        $repository = new \Modules\Iad\Repositories\Eloquent\EloquentScheduleRepository(new \Modules\Iad\Entities\Schedule());

        if (!config('app.cache')) {
          return $repository;
        }

        return new \Modules\Iad\Repositories\Cache\CacheScheduleDecorator($repository);
      }
    );
// add bindings


  }

  /**
   * Register components Blade
   */
  private function registerComponents()
  {
    Blade::componentNamespace("Modules\Iads\View\Components", 'iads');
  }

}
