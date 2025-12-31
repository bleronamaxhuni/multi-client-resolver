# Multi Client Resolver

A Laravel package to automatically resolve clients per request. Perfect for multi-tenant applications where you need to identify and scope data based on the current client.

## Features

- 🔍 **Multiple Detection Methods**: Support for subdomain, header, or URL parameter based client detection
- 🛡️ **Middleware Protection**: Automatically resolve and validate clients with built-in middleware
- 🎯 **Model Scoping**: Easy-to-use trait for automatically scoping Eloquent models to clients
- 🚀 **Simple API**: Clean helper function to access the current client anywhere in your application
- ⚙️ **Configurable**: Flexible configuration for different client detection strategies

## Installation

You can install the package via Composer:

```bash
composer require bleronamaxhuni/multi-client-resolver
```

## Configuration

Publish the configuration file:

```bash
php artisan vendor:publish --tag=config
```

This will create a `config/multi-client.php` file with the following options:

```php
return [
    // Detection method: 'subdomain', 'header', or 'url'
    'detection' => env('CLIENT_DETECTION', 'subdomain'),
    
    // Header key for API requests (when detection is 'header')
    'header_key' => 'X-Client-Code',
    
    // URL parameter name (when detection is 'url')
    'url_param' => 'client_id',
];
```

### Environment Variables

Add to your `.env` file:

```env
CLIENT_DETECTION=subdomain
```

## Setup

### 1. Client Model

Ensure you have a `Client` model in your application with a `slug` field:

```php
// app/Models/Client.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $fillable = ['name', 'slug'];
}
```

### 2. Database Migration

Your clients table should have at least these fields:

```php
Schema::create('clients', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('slug')->unique();
    $table->timestamps();
});
```

## Usage

### Client Detection Methods

#### Subdomain Detection

Detect clients based on the subdomain of the request:

```
https://client1.example.com → Resolves client with slug "client1"
```

Set in `.env`:
```env
CLIENT_DETECTION=subdomain
```

#### Header Detection

Detect clients from HTTP headers (useful for API requests):

```env
CLIENT_DETECTION=header
```

The package will look for the `X-Client-Code` header by default (configurable via `header_key`).

#### URL Parameter Detection

Detect clients from URL query parameters:

```
https://example.com?client_id=client1
```

Set in `.env`:
```env
CLIENT_DETECTION=url
```

### Using Middleware

Apply the middleware to routes or route groups where you want to automatically resolve the client:

```php
// routes/web.php
Route::middleware('client')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index']);
    // ... other routes
});
```

The middleware will:
1. Automatically resolve the client based on your configured detection method
2. Return a 404 error if the client is not found
3. Make the resolved client available via `app('currentClient')` or the `client()` helper

### Accessing the Current Client

Use the helper function anywhere in your application:

```php
use function Bleronamaxhuni\MultiClientResolver\client;

// Get the current client
$client = client();

if ($client) {
    echo $client->name;
}
```

Or use the service container directly:

```php
$client = app('currentClient');
```

### Model Scoping

Use the `ClientScoped` trait on your models to automatically scope queries to the current client:

```php
// app/Models/Product.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Bleronamaxhuni\MultiClientResolver\Traits\ClientScoped;

class Product extends Model
{
    use ClientScoped;
    
    protected $fillable = ['name', 'price', 'client_id'];
}
```

Now all queries on this model will automatically be scoped to the current client:

```php
// Only returns products for the current client
$products = Product::all();

// You can still query across all clients if needed
$allProducts = Product::withoutGlobalScope('client')->get();

// Or query for a specific client
$products = Product::forClient($someClient)->get();
```

**Important**: Make sure your model has a `client_id` column in the database.

### Manual Client Resolution

You can also manually resolve a client if needed:

```php
use Bleronamaxhuni\MultiClientResolver\ClientResolver;

$client = ClientResolver::resolve();
```

## Requirements

- PHP 7.4 or higher
- Laravel 8.0, 9.0, 10.0, or 11.0

## License

This package is open-sourced software licensed under the [MIT license](LICENSE).

## Author

**Blerona Maxhuni**

- Email: bleronamaxhunni@gmail.com

