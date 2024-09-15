<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Kreait\Firebase\Factory;

class FirebaseServiceProvider extends ServiceProvider

{
  /**
  * Register services.
  *
  * @return void
  */
  public function register() {
    $this->app->singleton('firebase', function ($app) {
      return (new Factory)->withServiceAccount(__DIR__.'/firebase_credentials.json')->withDatabaseUri('https://mydrugaida-1234-default-rtdb.firebaseio.com');
    });
  }

  /**
  * Bootstrap services.
  *
  * @return void
  */
  public function boot() {
    //
  }
}
?>