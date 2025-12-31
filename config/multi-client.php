<?php 

return [

    /*
    
    |--------------------------------------------------------------------------
    | Client detection method
    |--------------------------------------------------------------------------
    |
    | Choose how the package should detect the current client.
    | Options: 'subdomain', 'header', 'url'
    |
    */
    

    'detection'=>env('CLIENT_DETECTION','subdomain'),

    // Header key for API requests
    'header_key'=>'X-Client-Code',

    // URL parameter name for client detection
    'url_param'=>'client_id',
];