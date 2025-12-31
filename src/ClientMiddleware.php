<?php 

namespace Bleronamaxhuni\MultiClientResolver;

use Closure;
use Illuminate\Http\Request;

class ClientMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $client = ClientResolver::resolve();

        if(!$client){
            abort(404, 'Client not found');
        }

        return $next($request);
    }
}
