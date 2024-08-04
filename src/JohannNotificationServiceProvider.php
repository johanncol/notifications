<?php

namespace Johanncol\Notifications;

use Illuminate\Support\ServiceProvider;
use Johanncol\Notifications\Console\Commands\InstallCommand;

class JohannNotificationServiceProvider extends ServiceProvider
{

  /**
   * Register services.
   */
  public function register(): void
  {
      //
  }

  /**
   * Bootstrap services.
   */
  public function boot(): void
  {
    if ($this->app->runningInConsole())
    {
      $this->commands([
          InstallCommand::class,
          // NetworkCommand::class,
      ]);
    }
  }

}
