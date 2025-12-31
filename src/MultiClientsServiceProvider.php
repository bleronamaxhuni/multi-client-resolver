<?php 

namespace Bleronamaxhuni\MultiClientResolver;

use Illuminate\Support\ServiceProvider;

class MultiClientsServiceProvider extends ServiceProvider
{

    public function boot(){

        $this->publishes([
            __DIR__.'/../config/multi-client.php' => config_path('multi-client.php'),
        ], 'config');
        

        $router = $this->app['router'];
        $router->aliasMiddleware('client', ClientMiddleware::class);
    }


    public function register(){

        $this->mergeConfigFrom(__DIR__.'/../config/multi-client.php', 'multi-client');

        $this->app->singleton('currentClient', function(){
            return null;
        });
    }
}