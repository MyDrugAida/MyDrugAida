<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Spatie\Permission\Models\Role;
use Kreait\Firebase\Factory;


class AppServiceProvider extends ServiceProvider
{
  /**
  * Register any application services.
  */

  public function register() {
    $this->app->singleton('firebase.firestore', function ($app) {
      return (new Factory)->withServiceAccount(__DIR__.'/firebase_credentials.json')
      ->createFirestore()
      ->database();
    });
  }


  /**
  * Bootstrap any application services.
  */
  public function boot(): void
  {
    if (config('app.env') === 'production') {
      \URL::forceScheme('https');
    }
  }
}