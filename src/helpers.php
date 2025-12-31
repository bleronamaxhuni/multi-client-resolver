<?php

if(!function_exists('client')){

    /**
     * Get the current client resolved by the package
     *
     * @return \App\Models\Client|null
     */
    function client(){
        return app('currentClient');
    }
}