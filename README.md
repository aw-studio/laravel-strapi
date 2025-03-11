# Laravel Strapi

A Laravel package to integrate with Strapi CMS, providing an elegant way to interact with Strapi models and components.

## Installation

You can install the package via Composer:

```bash
composer require aw-studio/laravel-strapi
```

## Configuration

After installation, publish the configuration file:

```bash
php artisan vendor:publish --tag=laravel-strapi-config
```

Then, add the following environment variables to your `.env` file:

```env
STRAPI_BASE_URL=https://your-strapi-url.com
STRAPI_CACHE_ACTIVE=true # Set to false to disable caching
STRAPI_CACHE_TTL=3600 # Cache duration in seconds
```

## Strapi Models

You can create Strapi models using the following command:

```bash
php artisan make:strapi-model {name}
```

This will create a **SingleType** or **CollectionType** model in `App\Strapi\Models`.

### Querying Strapi Models

You can query models fluently:

```php
$post = Post::locale('de')
            ->where('Slug', $slug)
            ->populate([
                'Image' => [
                    'populate' => '*',
                ],
            ])
            ->first();
```

## Strapi Components

You can create Strapi components with the following command:

```bash
php artisan make:strapi-component {name?}
```

Each component represents a Strapi component (e.g., from dynamic zones). It consists of:
- A class file: `App\Strapi\Components\<CollectionName>\ComponentName.php`
- A corresponding Blade view file

### Registering Components

Components must be registered in the `config/laravel-strapi.php` configuration file.

### Populating Components

You can populate all components of a **Content** dynamic zone like this:

```php
$post = Post::locale('de')
            ->where('Slug', $slug)
            ->populateContent()
            ->first();
```

## Rendering Components in Blade

You can render dynamic zone components in Blade templates using the `x-dynamiczone` component:

```blade
<x-dynamiczone :items="$page->Content" />
```

## License

This package is open-sourced software licensed under the MIT license.


