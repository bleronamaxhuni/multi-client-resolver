<?php 

namespace Bleronamaxhuni\MultiClientResolver;

use Illuminate\Support\Facades\Facade;

class ClientResolver extends Facade
{

    public static function resolve(): ?object{
        
        $config = config('multi-client');

        $clientId = null;

        switch($config['detection']){
            case 'subdomain':
                $host = explode('.', request()->getHost());
                $clientId = $host[0];
                break;
            case 'header':
                $clientId = request()->header($config['header_key']) ?? null;
                break;
            case 'url':
                $clientId = request()->get($config['url_param']) ?? null;
                break;
        }

        if($clientId){
            $client = \App\Models\Client::where('slug', $clientId)->first();
            app()->instance('currentClient', $client);
            return $client;
        }

        return null;
    }
}